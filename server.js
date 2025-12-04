const path = require("path");
const express = require("express");
const morgan = require("morgan");
const { spawn } = require("child_process");
const nodemailer = require("nodemailer");

const app = express();
const PORT = process.env.PORT || 3000;

const mailStrategy = (process.env.MAIL_STRATEGY || "mail").toLowerCase();
const mailCommand = process.env.MAIL_CLI || (mailStrategy === "sendmail" ? "/usr/sbin/sendmail" : "mail");
const mailExtraArgs = (process.env.MAIL_EXTRA_ARGS || "")
  .split(/[\s,]+/)
  .map((entry) => entry.trim())
  .filter(Boolean);
const mailSupportsCustomSender = process.env.MAIL_DISABLE_R === "1" ? false : true;
const smtpConfig = {
  host: process.env.SMTP_HOST || "192.168.250.51",
  port: Number(process.env.SMTP_PORT || 25),
  secure: String(process.env.SMTP_SECURE || "false").toLowerCase() === "true",
  tls: {
    rejectUnauthorized: String(process.env.SMTP_TLS_REJECT_UNAUTHORIZED || "true").toLowerCase() === "true",
  },
};

if (process.env.SMTP_USER) {
  smtpConfig.auth = {
    user: process.env.SMTP_USER,
    pass: process.env.SMTP_PASS || "",
  };
}

let smtpTransport;
const getSmtpTransport = () => {
  if (!smtpTransport) {
    smtpTransport = nodemailer.createTransport(smtpConfig);
  }
  return smtpTransport;
};

// Middlewares
app.use(morgan(process.env.NODE_ENV === "production" ? "combined" : "dev"));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Static assets
const kasperskyDir = path.join(__dirname, "sites", "kaspersky");
const sonicwallDir = path.join(__dirname, "sites", "sonicwall");
const sharedAssetsDir = path.join(__dirname, "assets");

app.use("/assets", express.static(sharedAssetsDir));
app.use("/kaspersky", express.static(kasperskyDir));
app.use("/sonicwall", express.static(sonicwallDir));

const defaultFrom = process.env.MAIL_FROM || "no-reply@grupoimagetech.com.br";
const enforcedRecipients = process.env.MAIL_TO || "pmelo@grupoimagetech.com.br,lbittar@grupoimagetech.com.br";
const defaultRecipients = enforcedRecipients
  .split(",")
  .map((entry) => entry.trim())
  .filter(Boolean);

const requiredFields = ["nome", "email", "mensagem"];

const sanitize = (value = "") => (typeof value === "string" ? value.trim() : "");
const escapeHtml = (value = "") =>
  sanitize(value)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/\"/g, "&quot;")
    .replace(/'/g, "&#39;");

const buildEmailPayload = (body = {}) => {
  const nome = sanitize(body.nome);
  const email = sanitize(body.email);
  const empresa = sanitize(body.empresa);
  const telefone = sanitize(body.telefone);
  const mensagem = sanitize(body.mensagem);

  return {
    nome,
    email,
    empresa,
    telefone,
    mensagem,
  };
};

const isValidEmail = (value = "") => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
const resolveReplyTo = (payload) => (isValidEmail(payload.email) ? payload.email : undefined);

const buildText = (payload) =>
  [
    "Novo lead recebido via landing Kaspersky + Imagetech",
    `Nome: ${payload.nome || "(não informado)"}`,
    `Empresa: ${payload.empresa || "(não informado)"}`,
    `E-mail: ${payload.email || "(não informado)"}`,
    `Telefone: ${payload.telefone || "(não informado)"}`,
    "Mensagem:",
    payload.mensagem || "(sem mensagem)",
  ].join("\n");

const sendMailViaSmtp = ({ subject, body, from, recipients, replyTo }) => {
  const transporter = getSmtpTransport();
  return transporter.sendMail({
    from,
    to: recipients,
    subject,
    text: body,
    replyTo,
    envelope: {
      from,
      to: recipients,
    },
  });
};

const sendMailViaCli = ({ subject, body, from, recipients, replyTo }) =>
  new Promise((resolve, reject) => {
    if (!recipients.length) {
      return reject(new Error("Nenhum destinatário configurado."));
    }

    const args = [];
    if (mailStrategy === "sendmail") {
      args.push("-t", "-oi");
    } else {
      args.push("-s", subject);
      if (from && mailSupportsCustomSender) {
        args.push("-r", from);
      }
    }

    args.push(...mailExtraArgs);
    if (mailStrategy !== "sendmail") {
      args.push(...recipients);
    }

    const mail = spawn(mailCommand, args, { stdio: ["pipe", "ignore", "pipe"] });
    let stderr = "";

    mail.stderr.on("data", (chunk) => {
      stderr += chunk.toString();
    });

    mail.on("error", (error) => {
      if (error.code === "ENOENT") {
        error = new Error(
          `Comando de correio \"${mailCommand}\" não encontrado. Instale o binário ou configure a variável MAIL_CLI com o caminho correto.`
        );
      }
      reject(error);
    });

    mail.on("close", (code) => {
      if (code === 0) {
        resolve();
      } else {
        reject(new Error(stderr || `mail finalizado com código ${code}`));
      }
    });

    if (mailStrategy === "sendmail") {
      const headers = [
        from ? `From: ${from}` : null,
        `To: ${recipients.join(", ")}`,
        `Subject: ${subject}`,
        replyTo ? `Reply-To: ${replyTo}` : null,
      ]
        .filter(Boolean)
        .join("\n");

      mail.stdin.write(`${headers}\n\n${body}`);
    } else {
      mail.stdin.write(body);
    }
    mail.stdin.end();
  });

app.post("/api/lead", async (req, res) => {
  try {
    if (!defaultRecipients.length) {
      return res.status(500).json({ message: "Destinatário não configurado no servidor." });
    }

    const payload = buildEmailPayload(req.body);

    const missingFields = requiredFields.filter((field) => !payload[field]);
    if (missingFields.length) {
      return res.status(400).json({ message: `Preencha os campos obrigatórios: ${missingFields.join(", ")}.` });
    }

    const subject = `${process.env.MAIL_SUBJECT_PREFIX || "[Landing Kaspersky]"} ${payload.nome} - ${
      payload.empresa || "Contato"
    }`;

    const replyToEmail = resolveReplyTo(payload);
    const replyLine = replyToEmail ? `Responder para: ${replyToEmail}\n` : "";
    const body = `${replyLine}${buildText(payload)}\n`;

    const mailPayload = {
      subject,
      body,
      from: defaultFrom,
      recipients: defaultRecipients,
      replyTo: replyToEmail,
    };

    if (mailStrategy === "smtp") {
      await sendMailViaSmtp(mailPayload);
    } else {
      await sendMailViaCli(mailPayload);
    }

    res.status(200).json({ message: "Recebemos seus dados! Em breve faremos contato." });
  } catch (error) {
    console.error("Erro ao enviar lead:", error);
    res.status(502).json({ message: "Não foi possível enviar agora. Tente novamente em instantes." });
  }
});

app.get("/", (req, res) => {
  res.redirect(302, "/kaspersky");
});

app.get("*", (req, res) => {
  res.status(404).json({ message: "Conteúdo não encontrado." });
});

app.listen(PORT, () => {
  console.log(`Servidor rodando em http://localhost:${PORT}`);
});

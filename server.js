const path = require("path");
const express = require("express");
const morgan = require("morgan");
const { spawn } = require("child_process");

const app = express();
const PORT = process.env.PORT || 3000;

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

const sendMailViaCli = ({ subject, body, from, recipients }) =>
  new Promise((resolve, reject) => {
    if (!recipients.length) {
      return reject(new Error("Nenhum destinatário configurado."));
    }

    const args = ["-s", subject, "-r", from, ...recipients];
    const mail = spawn("mail", args, { stdio: ["pipe", "ignore", "pipe"] });
    let stderr = "";

    mail.stderr.on("data", (chunk) => {
      stderr += chunk.toString();
    });

    mail.on("error", (error) => {
      reject(error);
    });

    mail.on("close", (code) => {
      if (code === 0) {
        resolve();
      } else {
        reject(new Error(stderr || `mail finalizado com código ${code}`));
      }
    });

    mail.stdin.write(body);
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

    await sendMailViaCli({
      subject,
      body,
      from: defaultFrom,
      recipients: defaultRecipients,
    });

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

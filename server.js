const path = require("path");
const express = require("express");
const morgan = require("morgan");
const nodemailer = require("nodemailer");

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

// Email transporter configured to talk with Postfix (or any SMTP server)
const smtpHost = process.env.SMTP_HOST || "127.0.0.1";
const smtpPort = Number(process.env.SMTP_PORT || 25);
const smtpSecure = String(process.env.SMTP_SECURE || "false").toLowerCase() === "true";
const rejectUnauthorized = String(process.env.SMTP_TLS_REJECT_UNAUTHORIZED || "true").toLowerCase() !== "false";
const defaultFrom = process.env.MAIL_FROM || "no-reply@grupoimagetech.com.br";
const enforcedRecipients = ["pmelo@grupoimagetech.com.br", "lbittar@grupoimagetech.com.br"];
const defaultRecipients = (process.env.MAIL_TO || enforcedRecipients.join(","))
  .split(",")
  .map((entry) => entry.trim())
  .filter(Boolean);

const smtpAuth = process.env.SMTP_USER && process.env.SMTP_PASS ? { user: process.env.SMTP_USER, pass: process.env.SMTP_PASS } : undefined;

const transporter = nodemailer.createTransport({
  host: smtpHost,
  port: smtpPort,
  secure: smtpSecure,
  auth: smtpAuth,
  tls: {
    rejectUnauthorized,
  },
});

transporter.verify((error) => {
  if (error) {
    console.warn("[mail] Falha ao verificar transporte SMTP:", error.message);
  } else {
    console.log(`[mail] Transporte SMTP pronto para ${smtpHost}:${smtpPort}`);
  }
});

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

const buildHtml = (payload) => `
  <h2>Novo lead recebido via landing Kaspersky + Imagetech</h2>
  <ul>
    <li><strong>Nome:</strong> ${escapeHtml(payload.nome) || "(não informado)"}</li>
    <li><strong>Empresa:</strong> ${escapeHtml(payload.empresa) || "(não informado)"}</li>
    <li><strong>E-mail:</strong> ${escapeHtml(payload.email) || "(não informado)"}</li>
    <li><strong>Telefone:</strong> ${escapeHtml(payload.telefone) || "(não informado)"}</li>
  </ul>
  <p><strong>Mensagem:</strong></p>
  <p style="white-space: pre-line;">${escapeHtml(payload.mensagem) || "(sem mensagem)"}</p>
`;

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
    await transporter.sendMail({
      from: defaultFrom,
      to: defaultRecipients,
      replyTo: replyToEmail,
      envelope: {
        from: defaultFrom,
        to: defaultRecipients,
      },
      subject,
      text: buildText(payload),
      html: buildHtml(payload),
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

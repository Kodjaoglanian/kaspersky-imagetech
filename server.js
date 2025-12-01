const path = require("path");
const express = require("express");
const morgan = require("morgan");

const app = express();
const PORT = process.env.PORT || 3000;

// Middlewares
app.use(morgan(process.env.NODE_ENV === "production" ? "combined" : "dev"));
app.use(express.json());

// Static assets
const publicDir = path.join(__dirname);
app.use(express.static(publicDir));

// Placeholder endpoint for future form integration
app.post("/api/lead", (req, res) => {
  const payload = req.body || {};
  console.log("Lead recebido:", payload);
  res.status(202).json({ message: "Lead recebido. Em breve entraremos em contato." });
});

// Fallback to index.html for unknown routes (optional, helpful for SPAs)
app.get("*", (req, res) => {
  res.sendFile(path.join(publicDir, "index.html"));
});

app.listen(PORT, () => {
  console.log(`Servidor rodando em http://localhost:${PORT}`);
});

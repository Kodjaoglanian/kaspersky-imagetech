# Kaspersky + Grupo Imagetech Landing Page

> Landing page moderna e responsiva promocionando a parceria **Kaspersky + Grupo Imagetech**, com animações GSAP, formulário de captação corporativa e backend Node/Express pronto para integrações futuras. Também acompanha empacotamento completo em Docker.

## ✨ Destaques

- **Experiência premium**: Hero com ribbon de parceria, métricas animadas, cards com glassmorphism e seções dedicadas a benefícios, recursos, alianças e presença do parceiro.
- **Interação rica**: GSAP + IntersectionObserver para revelar conteúdo, CTA flutuante e cartões com efeito tilt.
- **Lead form integrado**: Envia dados via `fetch` para `/api/lead`, mostra estados de carregamento/sucesso/erro e já respeita LGPD.
- **Server-side pronto**: Express serve os arquivos estáticos e expõe endpoint para encaminhar leads a CRMs, filas ou e-mail.
- **Containerização**: Dockerfile multi-stage gera imagem enxuta pronta para produção (Node 20 Alpine).

## 🧱 Stack Técnico

| Camada         | Tecnologia / Biblioteca                           |
| -------------- | ------------------------------------------------- |
| UI/Estilos     | HTML5, CSS3 (flex/grid), Space Grotesk + Inter    |
| Animações      | GSAP 3.12, IntersectionObserver, hover tilt custom|
| Scripts        | Vanilla JS (ES2020)                               |
| Backend        | Node.js 20 + Express 4 + Morgan                   |
| Container      | Docker multi-stage (node:20-alpine)               |

## 📂 Estrutura

```
.
├── index.html          # Estrutura principal da landing page
├── style.css           # Temas, layout responsivo e componentes
├── script.js           # Navegação mobile, animações e form submit
├── server.js           # Servidor Express + endpoint /api/lead
├── package.json        # Dependências e scripts npm
├── package-lock.json
├── Dockerfile          # Build multi-stage para produção
├── .dockerignore       # Mantém a imagem enxuta
└── LOGO_escritabranca.png
```

## ⚙️ Requisitos

- **Node.js 18+** (desenvolvimento local)
- **npm 9+**
- **Docker 24+** (opcional para produção/containers)

> 💡 Em alguns discos externos/NTFS pode ser necessário instalar com `--no-bin-links`. Os comandos abaixo já consideram esse cenário.

## 🚀 Execução Local (Node.js)

```bash
npm install --no-bin-links
npm run dev
```

- A aplicação responde em `http://localhost:3000`.
- Altere a porta exportando `PORT=8080 npm run dev`.

## 🐳 Execução em Docker

```bash
# Build
docker build -t kaspersky-landing .

# Run
docker run --rm -p 3000:3000 kaspersky-landing

# Porta customizada (ex.: 8080)
docker run --rm -e PORT=8080 -p 8080:8080 kaspersky-landing
```

O container usa somente dependências de produção (`npm ci --omit=dev`), mantendo a imagem enxuta e segura.

## 🔌 API de Lead (`POST /api/lead`)

- Content-Type esperado: `application/json`
- Resposta padrão: `202 Accepted` + mensagem amigável
- O handler atual apenas faz `console.log` do payload; adapte-o para enviar e-mails, publicar em filas ou salvar em bancos.

### Exemplo de requisição

```bash
curl -X POST http://localhost:3000/api/lead \
   -H "Content-Type: application/json" \
   -d '{
            "nome": "Ana Souza",
            "empresa": "Imagetech",
            "email": "ana@imagetech.com",
            "telefone": "+55 11 99999-0000",
            "mensagem": "Quero ativar proteção para 500 endpoints."
         }'
```

### Onde modificar

```js
// server.js
app.post("/api/lead", (req, res) => {
   const payload = req.body;
   // TODO: enviar para CRM, e-mail, fila, etc.
   res.status(202).json({ message: "Lead recebido. Em breve entraremos em contato." });
});
```

## 💻 Interação do Formulário (front-end)

- `script.js` captura o submit, faz `fetch` para `/api/lead`, bloqueia o botão durante o envio e atualiza `.form-status` com feedback ao usuário.
- A integração já funciona tanto no Node local quanto no container, sem ajustes extras.

## 🧭 Guia Visual das Seções

1. **Hero & Ribbon** – CTA principal, métricas e destaque para a colaboração com Imagetech.
2. **Soluções Essenciais** – Três pilares apresentados em cards com efeito tilt 3D.
3. **Parceria** – Bloco único explicando a operação conjunta e playbooks disponíveis.
4. **Clientes** – Depoimentos curtos que reforçam a simplicidade da solução.
5. **Contato** – Lead form corporativo e CTA flutuante para acionar o desk.

## 🧪 Checklist / Boas Práticas

- ✅ Responsivo (desktop → mobile)
- ✅ Acessibilidade básica (aria-labels, `role="status"`, foco em nav mobile)
- ✅ Animado, porém performático (InterObs + GSAP com fallback)
- ✅ Pronto para CI/CD containerizado

## ➕ Próximos Passos Sugeridos

1. Conectar `/api/lead` a um CRM (HubSpot, RD, Salesforce) ou fila (SQS, RabbitMQ).
2. Adicionar validações server-side extras e proteção (Rate limiting, CAPTCHA).
3. Configurar deploy automatizado (GitHub Actions → Registry → Host).
4. Instrumentar analytics/observabilidade (GA4, Plausible, OpenTelemetry).

---

Feito com foco em performance, storytelling e prontos para ativar squads de segurança da **Imagetech + Kaspersky**. Ajuste, expanda e conecte às suas ferramentas corporativas conforme necessário. 🚀

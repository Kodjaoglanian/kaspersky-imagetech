# Kaspersky + Grupo Imagetech Landing Page

> Landing page moderna e responsiva promocionando a parceria **Kaspersky + Grupo Imagetech**, com animações GSAP, formulário de captação corporativa e backend Node/Express pronto para integrações futuras. Também acompanha empacotamento completo em Docker.

## ✨ Destaques

- **Duas jornadas premium**: `/kaspersky` mantém o storytelling de proteção endpoint da Kaspersky e `/sonicwall` entrega uma versão laranja/navy focada em edge security.
- **Experiência premium**: Hero com ribbon de parceria, métricas animadas, cards com glassmorphism e seções dedicadas a benefícios, recursos, alianças e presença do parceiro.
- **Interação rica**: GSAP + IntersectionObserver para revelar conteúdo, CTA flutuante e cartões com efeito tilt.
- **Lead form integrado**: Envia dados via `fetch` para `/api/lead`, mostra estados de carregamento/sucesso/erro e já respeita LGPD.
- **Server-side com e-mail**: Express serve os arquivos estáticos e encaminha os leads via SMTP direto para o relay (ex.: `192.168.250.51`) ou, opcionalmente, por comandos CLI (`mail`/`sendmail`).
- **Containerização**: Dockerfile multi-stage gera imagem enxuta pronta para produção (Node 20 Alpine).

## 🧱 Stack Técnico

| Camada         | Tecnologia / Biblioteca                           |
| -------------- | ------------------------------------------------- |
| UI/Estilos     | HTML5, CSS3 (flex/grid), Space Grotesk + Inter    |
| Animações      | GSAP 3.12, IntersectionObserver, hover tilt custom|
| Scripts        | Vanilla JS (ES2020)                               |
| Backend        | Node.js 20 + Express 4 + Morgan + Nodemailer      |
| Container      | Docker multi-stage (node:20-alpine)               |

## 📂 Estrutura

```
.
├── server.js                # Express + API de lead + static routing
├── package.json / lock
├── Dockerfile / .dockerignore
├── assets/
│   ├── images/LOGO_escritabranca.png
│   └── logos/*.png          # Carrossel compartilhado
├── sites/
│   ├── kaspersky/
│   │   ├── index.html
│   │   ├── style.css
│   │   └── script.js
│   └── sonicwall/
│       ├── index.html
│       ├── style.css
│       └── script.js
├── wordpress-theme/
│   └── kaspersky-imagetech/  # Tema WP completo
└── README.md
```

## 🌐 Landings disponíveis

| Caminho                                 | Foco narrativo                             | Destaques rápidos |
| --------------------------------------- | ----------------------------------------- | ----------------- |
| `https://{host}/kaspersky`              | Proteção endpoint + XDR da Kaspersky       | Ribbon verde, métricas globais, cards tilt e CTA "Solicitar proposta" |
| `https://{host}/sonicwall`              | Edge security, firewalls & SD-WAN SonicWall| Paleta navy + amber, copy Zero Trust, carrossel com novos chips e CTA "Quero SonicWall" |

Ambas consomem o mesmo backend (`/api/lead`) e o mesmo conjunto de assets compartilhados em `/assets`. O front-end de cada brand é independente (HTML/CSS dedicados), então dá para evoluir copy ou visuais de forma isolada.

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

- A aplicação responde em `http://localhost:3000/kaspersky` e `http://localhost:3000/sonicwall` (a raiz `/` redireciona para Kaspersky por padrão).
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

As duas journeys continuarão acessíveis em `/kaspersky` e `/sonicwall` (ajuste o domínio/porta conforme o ambiente).

Para que o container fale com o Postfix instalado no host Linux, adicione a entrada `host.docker.internal` apontando para o gateway:

```bash
docker run --rm \
   --env-file .env \  # copie .env.example e ajuste os e-mails/host
   --add-host=host.docker.internal:host-gateway \
   -p 3000:3000 kaspersky-landing
```

> 💡 Em ambientes que já expõem Postfix via DNS interno, basta apontar `SMTP_HOST` para o hostname/IP correspondente e remover o parâmetro `--add-host`.

O container usa somente dependências de produção (`npm ci --omit=dev`), mantendo a imagem enxuta e segura.

### Docker Compose

Para evitar redigitar o comando longo, há um `docker-compose.yml` na raiz. Ele já embute o build, publica a porta `3009` e injeta o alias `host.docker.internal`:

```bash
docker compose up -d --build
# Para parar/remover
docker compose down
```

Altere o mapeamento de porta ou o arquivo `.env` conforme necessário antes de subir.

## 📮 Variáveis de Ambiente (SMTP/Postfix)

Configure-as via `.env` ou diretamente no `docker run`/serviço:

| Variável                          | Padrão                             | Descrição |
| --------------------------------- | ---------------------------------- | --------- |
| `PORT`                            | `3000`                             | Porta HTTP do Express |
| `MAIL_STRATEGY`                   | `mail`                             | `smtp` para relay direto, `mail` (CLI) ou `sendmail` |
| `SMTP_HOST`                       | `192.168.250.51`                   | Host do Postfix/SMTP (quando `MAIL_STRATEGY=smtp`) |
| `SMTP_PORT`                       | `25`                               | Porta do Postfix/SMTP |
| `SMTP_SECURE`                     | `false`                            | Define conexão SMTPS (não usar para porta 25 plana) |
| `SMTP_TLS_REJECT_UNAUTHORIZED`    | `true`                             | Coloque `false` se o certificado for self-signed |
| `SMTP_USER` / `SMTP_PASS`         | _vazio_                            | Preencha somente se o relay exigir autenticação |
| `MAIL_CLI`                        | `mail`                             | Caminho do binário `mail`/`sendmail` quando não usar SMTP |
| `MAIL_EXTRA_ARGS`                 | _vazio_                            | Flags adicionais para o comando CLI |
| `MAIL_DISABLE_R`                  | `0`                                | Coloque `1` se o comando não aceitar `-r` para o remetente |
| `MAIL_FROM`                       | `no-reply@grupoimagetech.com.br`   | Remetente aplicado ao envelope e cabeçalho |
| `MAIL_TO`                         | `pmelo@grupoimagetech.com.br,lbittar@grupoimagetech.com.br` | Um ou mais destinatários separados por vírgula |
| `MAIL_SUBJECT_PREFIX`             | `[Landing Kaspersky]`              | Prefixo do assunto |

> Há um `.env.example` pronto para servir de base (`cp .env.example .env`). O servidor carrega automaticamente qualquer arquivo `.env` na raiz via `dotenv`, então basta reiniciar o processo/serviço após atualizar os valores.

### Exemplo: relay interno 192.168.250.51 (sem autenticação)

```dotenv
MAIL_STRATEGY=smtp
SMTP_HOST=192.168.250.51
SMTP_PORT=25
SMTP_SECURE=false
SMTP_TLS_REJECT_UNAUTHORIZED=false
SMTP_USER=
SMTP_PASS=
MAIL_FROM=no-reply@grupoimagetech.com.br
MAIL_TO=pmelo@grupoimagetech.com.br,lbittar@grupoimagetech.com.br
```

Com esse ajuste o backend passa a falar diretamente com o Postfix do host, mantendo o remetente imposto e sem exigir autenticação. Se preferir continuar usando o comando `mail` do próprio servidor, basta trocar `MAIL_STRATEGY` para `mail` (ou `sendmail`) e garantir que o binário esteja disponível na imagem/VM.

## 🔌 API de Lead (`POST /api/lead`)

- Content-Type esperado: `application/json`
- Resposta padrão: `200 OK` + mensagem amigável (ou `4xx/5xx` em caso de erro)
- No backend o payload é validado, higienizado e encaminhado via SMTP/Postfix usando Nodemailer.

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
// server.js (trecho)
await transporter.sendMail({
   from: process.env.MAIL_FROM || "no-reply@grupoimagetech.com.br",
   to: defaultRecipients,
   replyTo: isValidEmail(payload.email) ? payload.email : undefined,
   envelope: { from: process.env.MAIL_FROM || "no-reply@grupoimagetech.com.br", to: defaultRecipients },
   subject,
   text: buildText(payload),
   html: buildHtml(payload),
});
```

## 💻 Interação do Formulário (front-end)

- Cada brand possui seu próprio `script.js` (em `sites/<brand>/`) que captura o submit, faz `fetch` para `/api/lead`, bloqueia o botão durante o envio e atualiza `.form-status` com feedback ao usuário.
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

1. Adicionar salvamento assíncrono (CRM/filas) mantendo o envio via Postfix.
2. Incluir proteções adicionais (rate limit, CAPTCHA) no endpoint `/api/lead`.
3. Configurar deploy automatizado (GitHub Actions → Registry → Host).
4. Instrumentar analytics/observabilidade (GA4, Plausible, OpenTelemetry).

## 🧩 Tema WordPress pronto

Além da versão estática/Dockerizada, o repositório agora traz um tema WordPress completo em `wordpress-theme/kaspersky-imagetech/`, contendo:

```
wordpress-theme/
└── kaspersky-imagetech/
   ├── style.css              # Cabeçalho do tema + metadata
   ├── functions.php          # Enfilestra assets, menu walker e Ajax do formulário
   ├── front-page.php         # Página única com todas as seções da landing
   ├── header.php / footer.php
   └── assets/
      ├── css/main.css       # Mesmo visual da landing original
      ├── js/main.js         # Navegação mobile, animações e Ajax (via admin-ajax.php)
      └── images/LOGO_escritabranca.png
```

**Como instalar rapidamente**

1. Comprima a pasta `kaspersky-imagetech` ou copie-a para `wp-content/themes` do seu projeto WordPress.
2. Ative o tema. Na primeira ativação ele cria automaticamente a página **“Kaspersky Landing”** já preenchida com o padrão de blocos e, se ainda não houver página inicial definida, deixa ela como *Página Inicial*.
3. Para editar o site todo, abra a página “Kaspersky Landing” no editor de blocos (Gutenberg): cada seção (hero, soluções, parceria, contato, rodapé, CTA flutuante) está em blocos/grupos com as mesmas classes CSS — basta trocar textos, imagens ou duplicar cards diretamente pelo editor visual. Caso queira inserir o layout em outra página, use **Inserir ▸ Padrões ▸ Kaspersky Imagetech ▸ Landing page completa**.
4. O menu/CTA/mobile menu fazem parte dos blocos atuais, então qualquer alteração é feita na própria página (não há dependência de *Aparência ▸ Menus*). O formulário permanece integrado ao `admin-ajax.php` (`kaspersky_submit_lead`) e envia e-mails para o `admin_email`.

> ℹ️ O tema já inclui GSAP via CDN, animações, CTA flutuante e fallback para os anchors originais caso nenhum menu seja cadastrado.

---

Feito com foco em performance, storytelling e prontos para ativar squads de segurança da **Imagetech + Kaspersky**. Ajuste, expanda e conecte às suas ferramentas corporativas conforme necessário. 🚀

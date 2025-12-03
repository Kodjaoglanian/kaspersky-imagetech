<?php
$logo_url = esc_url(KASPERSKY_IMAGETECH_URI . '/assets/images/LOGO_escritabranca.png');
$hero_image = esc_url('https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=800&q=80');
$current_year = esc_html(gmdate('Y'));

return [
    'title' => __('Landing page completa', 'kaspersky-imagetech'),
    'description' => __('Layout integral da landing editável pelo editor de blocos.', 'kaspersky-imagetech'),
    'categories' => ['kaspersky-imagetech', 'pages'],
    'content' => <<<HTML
<!-- wp:group {"tagName":"header","className":"hero","anchor":"hero","layout":{"type":"constrained"}} -->
<header class="wp-block-group hero" id="hero">
  <!-- wp:html --><div class="hero-glow"></div><!-- /wp:html -->
  <!-- wp:html --><div class="hero-grid"></div><!-- /wp:html -->
  <!-- wp:group {"tagName":"nav","className":"nav","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
  <nav class="wp-block-group nav">
    <!-- wp:group {"className":"brand-signature","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"flex-start","verticalAlignment":"center"}} -->
    <div class="wp-block-group brand-signature">
      <!-- wp:paragraph {"className":"logo"} -->
      <p class="logo">Kaspersky</p>
      <!-- /wp:paragraph -->
      <!-- wp:html --><span class="brand-divider" aria-hidden="true"></span><!-- /wp:html -->
      <!-- wp:group {"className":"partner-mark"} -->
      <div class="wp-block-group partner-mark">
        <!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
        <figure class="wp-block-image size-full"><img src="{$logo_url}" alt="Grupo Imagetech" /></figure>
        <!-- /wp:image -->
      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
    <!-- wp:list {"className":"nav-links"} -->
    <ul class="nav-links">
      <li><a href="#solutions"><span class="nav-link-title">Soluções</span><span class="nav-link-sub">1º camada</span></a></li>
      <li><a href="#alliance"><span class="nav-link-title">Parceria</span><span class="nav-link-sub">Imagetech</span></a></li>
      <li><a href="#testimonials"><span class="nav-link-title">Clientes</span><span class="nav-link-sub">Confiança</span></a></li>
      <li><a href="#contact"><span class="nav-link-title">Contato</span><span class="nav-link-sub">Ative agora</span></a></li>
    </ul>
    <!-- /wp:list -->
    <!-- wp:html -->
    <button class="nav-toggle" aria-label="Abrir menu" aria-expanded="false">
      <span></span>
      <span></span>
    </button>
    <!-- /wp:html -->
  </nav>
  <!-- /wp:group -->
  <!-- wp:group {"className":"mobile-menu"} -->
  <div class="wp-block-group mobile-menu">
    <!-- wp:list {"className":"mobile-nav-links"} -->
    <ul class="mobile-nav-links">
      <li><a href="#solutions">Soluções</a></li>
      <li><a href="#alliance">Parceria</a></li>
      <li><a href="#testimonials">Clientes</a></li>
      <li><a href="#contact">Contato</a></li>
    </ul>
    <!-- /wp:list -->
  </div>
  <!-- /wp:group -->
  <!-- wp:group {"className":"hero-content","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
  <div class="wp-block-group hero-content">
    <!-- wp:group {"className":"hero-text","layout":{"type":"flex","orientation":"vertical"}} -->
    <div class="wp-block-group hero-text">
      <!-- wp:paragraph {"className":"eyebrow"} -->
      <p class="eyebrow">Segurança Digital Avançada</p>
      <!-- /wp:paragraph -->
      <!-- wp:heading {"level":1} -->
      <h1>Proteção Total com Kaspersky Antivirus</h1>
      <!-- /wp:heading -->
      <!-- wp:paragraph -->
      <p>Uma plataforma inteligente que combina prevenção, detecção e resposta em tempo real para garantir tranquilidade em qualquer dispositivo.</p>
      <!-- /wp:paragraph -->
      <!-- wp:group {"className":"hero-actions"} -->
      <div class="wp-block-group hero-actions"><a class="btn primary" href="#contact">Solicitar proposta</a></div>
      <!-- /wp:group -->
      <!-- wp:group {"className":"hero-stats","layout":{"type":"flex","flexWrap":"wrap"}} -->
      <div class="wp-block-group hero-stats">
        <!-- wp:group {"className":"hero-stat"} -->
        <div class="wp-block-group hero-stat">
          <!-- wp:paragraph {"className":"hero-stat-value"} -->
          <p><strong>400M+</strong></p>
          <!-- /wp:paragraph -->
          <!-- wp:paragraph {"className":"hero-stat-label"} -->
          <p><span>Usuários protegidos</span></p>
          <!-- /wp:paragraph -->
        </div>
        <!-- /wp:group -->
        <!-- wp:group {"className":"hero-stat"} -->
        <div class="wp-block-group hero-stat">
          <!-- wp:paragraph {"className":"hero-stat-value"} -->
          <p><strong>250K+</strong></p>
          <!-- /wp:paragraph -->
          <!-- wp:paragraph {"className":"hero-stat-label"} -->
          <p><span>Ameaças bloqueadas/dia</span></p>
          <!-- /wp:paragraph -->
        </div>
        <!-- /wp:group -->
        <!-- wp:group {"className":"hero-stat"} -->
        <div class="wp-block-group hero-stat">
          <!-- wp:paragraph {"className":"hero-stat-value"} -->
          <p><strong>200+</strong></p>
          <!-- /wp:paragraph -->
          <!-- wp:paragraph {"className":"hero-stat-label"} -->
          <p><span>Países atendidos</span></p>
          <!-- /wp:paragraph -->
        </div>
        <!-- /wp:group -->
        <!-- wp:group {"className":"hero-stat"} -->
        <div class="wp-block-group hero-stat">
          <!-- wp:paragraph {"className":"hero-stat-value"} -->
          <p><strong>30+ squads</strong></p>
          <!-- /wp:paragraph -->
          <!-- wp:paragraph {"className":"hero-stat-label"} -->
          <p><span>Especialistas Grupo Imagetech</span></p>
          <!-- /wp:paragraph -->
        </div>
        <!-- /wp:group -->
      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
    <!-- wp:group {"className":"hero-visual"} -->
    <div class="wp-block-group hero-visual">
      <!-- wp:image {"sizeSlug":"large"} -->
      <figure class="wp-block-image size-large"><img src="{$hero_image}" alt="Ilustração de segurança digital" /></figure>
      <!-- /wp:image -->
      <!-- wp:group {"className":"hero-card"} -->
      <div class="wp-block-group hero-card">
        <p>Escudos Ativos</p>
        <strong>Monitoramento 24/7</strong>
      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
  </div>
  <!-- /wp:group -->
</header>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"solutions","anchor":"solutions","layout":{"type":"constrained"}} -->
<section class="wp-block-group solutions" id="solutions">
  <!-- wp:group {"className":"section-header","layout":{"type":"constrained"}} -->
  <div class="wp-block-group section-header">
    <!-- wp:paragraph {"className":"eyebrow"} -->
    <p class="eyebrow">Essencial e direto</p>
    <!-- /wp:paragraph -->
    <!-- wp:heading -->
    <h2>Três pilares para proteger sua operação</h2>
    <!-- /wp:heading -->
    <!-- wp:paragraph -->
    <p>Foco no que mais importa: visibilidade, resposta rápida e suporte próximo.</p>
    <!-- /wp:paragraph -->
  </div>
  <!-- /wp:group -->
  <!-- wp:columns {"className":"solution-grid"} -->
  <div class="wp-block-columns solution-grid">
    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:group {"className":"solution-card"} -->
      <div class="wp-block-group solution-card">
        <!-- wp:html -->
        <div class="icon-shell"><span class="icon">🛡</span></div>
        <!-- /wp:html -->
        <!-- wp:heading {"level":3} -->
        <h3>Proteção em tempo real</h3>
        <!-- /wp:heading -->
        <!-- wp:paragraph -->
        <p>Inteligência Kaspersky detecta e neutraliza ameaças instantaneamente, mantendo todos os endpoints blindados.</p>
        <!-- /wp:paragraph -->
      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:column -->
    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:group {"className":"solution-card"} -->
      <div class="wp-block-group solution-card">
        <!-- wp:html -->
        <div class="icon-shell"><span class="icon">🌐</span></div>
        <!-- /wp:html -->
        <!-- wp:heading {"level":3} -->
        <h3>Controle centralizado</h3>
        <!-- /wp:heading -->
        <!-- wp:paragraph -->
        <p>Dashboards objetivos para governar políticas, alertas e relatórios sem ruído ou camadas desnecessárias.</p>
        <!-- /wp:paragraph -->
      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:column -->
    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:group {"className":"solution-card"} -->
      <div class="wp-block-group solution-card">
        <!-- wp:html -->
        <div class="icon-shell"><span class="icon">🤝</span></div>
        <!-- /wp:html -->
        <!-- wp:heading {"level":3} -->
        <h3>Operação assistida</h3>
        <!-- /wp:heading -->
        <!-- wp:paragraph -->
        <p>Times dedicados da Imagetech acompanham cada etapa, do kickoff ao suporte contínuo, sempre em português.</p>
        <!-- /wp:paragraph -->
      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:column -->
  </div>
  <!-- /wp:columns -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"alliance","layout":{"type":"constrained"}} -->
<section class="wp-block-group alliance" id="alliance">
  <!-- wp:columns {"className":"alliance-shell"} -->
  <div class="wp-block-columns alliance-shell">
    <!-- wp:column {"className":"alliance-copy"} -->
    <div class="wp-block-column alliance-copy">
      <!-- wp:paragraph {"className":"eyebrow"} -->
      <p class="eyebrow">Parceria oficial</p>
      <!-- /wp:paragraph -->
      <!-- wp:heading -->
      <h2>Kaspersky + Grupo Imagetech</h2>
      <!-- /wp:heading -->
      <!-- wp:paragraph -->
      <p>Uma única frente para implementar, operar e evoluir a segurança digital da sua empresa.</p>
      <!-- /wp:paragraph -->
      <!-- wp:list {"className":"alliance-points"} -->
      <ul class="alliance-points">
        <li>Arquitetura homologada e adaptada ao seu ambiente.</li>
        <li>Desk em português com resposta em até 4h úteis.</li>
        <li>Governança compartilhada e relatórios executivos contínuos.</li>
      </ul>
      <!-- /wp:list -->
    </div>
    <!-- /wp:column -->
    <!-- wp:column {"className":"alliance-panel"} -->
    <div class="wp-block-column alliance-panel">
      <!-- wp:group -->
      <div class="wp-block-group">
        <!-- wp:heading {"level":3} -->
        <h3>Squad dedicado</h3>
        <!-- /wp:heading -->
        <!-- wp:paragraph -->
        <p>Especialistas Imagetech atuam lado a lado com laboratórios Kaspersky para acelerar decisões.</p>
        <!-- /wp:paragraph -->
      </div>
      <!-- /wp:group -->
      <!-- wp:group -->
      <div class="wp-block-group">
        <!-- wp:heading {"level":3} -->
        <h3>Playbooks prontos</h3>
        <!-- /wp:heading -->
        <!-- wp:paragraph -->
        <p>Runbooks certificados para incidentes, auditorias e expansões, reduzindo tempo de reação.</p>
        <!-- /wp:paragraph -->
      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:column -->
  </div>
  <!-- /wp:columns -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"testimonials","layout":{"type":"constrained"}} -->
<section class="wp-block-group testimonials" id="testimonials">
  <!-- wp:group {"className":"section-header"} -->
  <div class="wp-block-group section-header">
    <!-- wp:paragraph {"className":"eyebrow"} -->
    <p class="eyebrow">Clientes</p>
    <!-- /wp:paragraph -->
    <!-- wp:heading -->
    <h2>Menos ruído, mais segurança</h2>
    <!-- /wp:heading -->
    <!-- wp:paragraph -->
    <p>Companhias que simplificaram a proteção mantendo o padrão Kaspersky.</p>
    <!-- /wp:paragraph -->
  </div>
  <!-- /wp:group -->
  <!-- wp:group {"className":"logo-carousel","layout":{"type":"constrained"}} -->
  <div class="wp-block-group logo-carousel" aria-label="Clientes que confiam">
    <!-- wp:paragraph {"className":"logo-track"} -->
    <p class="logo-track"><span class="logo-item">NovaWave</span><span class="logo-item">ArboPay</span><span class="logo-item">Atlas Connect</span><span class="logo-item">Digitronix</span><span class="logo-item">CorePulse</span><span class="logo-item">NovaWave</span><span class="logo-item">ArboPay</span><span class="logo-item">Atlas Connect</span><span class="logo-item">Digitronix</span><span class="logo-item">CorePulse</span></p>
    <!-- /wp:paragraph -->
  </div>
  <!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"contact","anchor":"contact","layout":{"type":"constrained"}} -->
<section class="wp-block-group contact" id="contact">
  <!-- wp:group {"className":"section-header"} -->
  <div class="wp-block-group section-header">
    <!-- wp:paragraph {"className":"eyebrow"} -->
    <p class="eyebrow">Captação corporativa</p>
    <!-- /wp:paragraph -->
    <!-- wp:heading -->
    <h2>Ative sua operação com Kaspersky + Grupo Imagetech</h2>
    <!-- /wp:heading -->
    <!-- wp:paragraph -->
    <p>Preencha os dados e receba uma proposta com arquitetura recomendada, cronograma de implantação e equipe dedicada.</p>
    <!-- /wp:paragraph -->
  </div>
  <!-- /wp:group -->
  <!-- wp:columns {"className":"contact-wrap"} -->
  <div class="wp-block-columns contact-wrap">
    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:group {"className":"contact-card"} -->
      <div class="wp-block-group contact-card">
        <p class="badge">Desk dedicado</p>
        <!-- wp:heading {"level":3} -->
        <h3>Converse com especialistas</h3>
        <!-- /wp:heading -->
        <!-- wp:paragraph -->
        <p>Equipes comerciais e técnicas trabalham em conjunto para mapear requisitos, dimensionar licenciamento e coordenar provas de conceito. Atendimento nacional, remoto ou presencial.</p>
        <!-- /wp:paragraph -->
        <!-- wp:list {"className":"contact-list"} -->
        <ul class="contact-list">
          <li><strong>Response Desk</strong><span>Retorno em até 4h úteis com plano de ação inicial.</span></li>
          <li><strong>Arquitetura validada</strong><span>Desenho técnico assinado por especialistas Imagetech certificados pela Kaspersky.</span></li>
          <li><strong>Suporte executivo</strong><span>Briefings para board, com indicadores e roadmap de implementação.</span></li>
        </ul>
        <!-- /wp:list -->
        <!-- wp:group {"className":"contact-stats","layout":{"type":"flex"}} -->
        <div class="wp-block-group contact-stats">
          <!-- wp:group {"className":"contact-stat"} -->
          <div class="wp-block-group contact-stat">
            <!-- wp:paragraph -->
            <p><strong>+1.200</strong></p>
            <!-- /wp:paragraph -->
            <!-- wp:paragraph -->
            <p><span>Projetos corporativos</span></p>
            <!-- /wp:paragraph -->
          </div>
          <!-- /wp:group -->
          <!-- wp:group {"className":"contact-stat"} -->
          <div class="wp-block-group contact-stat">
            <!-- wp:paragraph -->
            <p><strong>98%</strong></p>
            <!-- /wp:paragraph -->
            <!-- wp:paragraph -->
            <p><span>Satisfação no suporte</span></p>
            <!-- /wp:paragraph -->
          </div>
          <!-- /wp:group -->
        </div>
        <!-- /wp:group -->
      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:column -->
    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:group {"className":"contact-form"} -->
      <div class="wp-block-group contact-form">
        <!-- wp:html -->
        <form class="lead-form" method="post" novalidate>
          <label>
            Nome completo
            <input type="text" name="nome" placeholder="Seu nome" required />
          </label>
          <label>
            Empresa
            <input type="text" name="empresa" placeholder="Nome da organização" required />
          </label>
          <label>
            E-mail corporativo
            <input type="email" name="email" placeholder="voce@empresa.com" required />
          </label>
          <label>
            Telefone / WhatsApp
            <input type="tel" name="telefone" placeholder="(00) 00000-0000" />
          </label>
          <label>
            Necessidade principal
            <textarea name="mensagem" rows="4" placeholder="Explique seus desafios de segurança"></textarea>
          </label>
          <button type="submit" class="btn primary">Quero falar com o time</button>
          <p class="form-status" role="status" aria-live="polite"></p>
          <p class="form-note">Os dados são tratados em conformidade com a LGPD. Retorno em até 1 dia útil.</p>
        </form>
        <!-- /wp:html -->
      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:column -->
  </div>
  <!-- /wp:columns -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"footer","className":"footer","layout":{"type":"constrained"}} -->
<footer class="wp-block-group footer">
  <!-- wp:group {"className":"footer-content","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
  <div class="wp-block-group footer-content">
    <!-- wp:group {"className":"footer-col"} -->
    <div class="wp-block-group footer-col">
      <div class="logo">Kaspersky</div>
      <!-- wp:paragraph -->
      <p>Proteção confiável para o presente e o futuro digital.</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->
    <!-- wp:group {"className":"footer-col"} -->
    <div class="wp-block-group footer-col">
      <!-- wp:heading {"level":4} -->
      <h4>Links</h4>
      <!-- /wp:heading -->
      <!-- wp:list -->
      <ul>
        <li><a href="#hero">Início</a></li>
        <li><a href="#solutions">Soluções</a></li>
        <li><a href="#alliance">Parceria</a></li>
        <li><a href="#contact">Contato</a></li>
      </ul>
      <!-- /wp:list -->
    </div>
    <!-- /wp:group -->
    <!-- wp:group {"className":"footer-col"} -->
    <div class="wp-block-group footer-col">
      <!-- wp:heading {"level":4} -->
      <h4>Legal</h4>
      <!-- /wp:heading -->
      <!-- wp:list -->
      <ul>
        <li><a href="#">Política de Privacidade</a></li>
        <li><a href="#">Termos de Uso</a></li>
        <li><a href="#">LGPD</a></li>
      </ul>
      <!-- /wp:list -->
    </div>
    <!-- /wp:group -->
    <!-- wp:group {"className":"footer-col"} -->
    <div class="wp-block-group footer-col">
      <!-- wp:heading {"level":4} -->
      <h4>Redes</h4>
      <!-- /wp:heading -->
      <!-- wp:group {"className":"social","layout":{"type":"flex","flexWrap":"wrap"}} -->
      <div class="wp-block-group social">
        <a href="#" aria-label="LinkedIn">in</a>
        <a href="#" aria-label="Twitter">tw</a>
        <a href="#" aria-label="YouTube">yt</a>
      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
  </div>
  <!-- /wp:group -->
  <!-- wp:group {"className":"partner-footer","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"left"}} -->
  <div class="wp-block-group partner-footer">
    <img src="{$logo_url}" alt="Grupo Imagetech" />
    <p>O Grupo Imagetech é referência em tecnologia, infraestrutura e soluções corporativas. Com atuação nacional e squads especializados, conduz arquitetura, implantação e sustentação de plataformas críticas em parceria com a Kaspersky.</p>
  </div>
  <!-- /wp:group -->
  <!-- wp:paragraph {"className":"footer-note"} -->
  <p class="footer-note">© {$current_year} Kaspersky Lab. Todos os direitos reservados.</p>
  <!-- /wp:paragraph -->
</footer>
<!-- /wp:group -->

<!-- wp:html -->
<a href="#contact" class="floating-cta" aria-label="Falar com especialistas">
  <span>Falar com especialistas</span>
</a>
<!-- /wp:html -->
HTML,
];

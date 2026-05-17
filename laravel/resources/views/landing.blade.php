<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  {{-- ===== SEO PRIMÁRIO ===== --}}
  <title>GuildaByte | Criação de Sites e Sistemas para Pequenos Negócios</title>
  <meta name="description" content="Criamos sites, lojas virtuais, sistemas de agendamento e painéis administrativos para barbearias, fotógrafos, confeitarias e pequenos negócios. Entrega em até 30 dias.">
  <link rel="canonical" href="{{ url()->current() }}">
  <meta name="robots" content="index, follow">

  {{-- ===== OPEN GRAPH (WhatsApp, LinkedIn, redes sociais) ===== --}}
  <meta property="og:type"        content="website">
  <meta property="og:locale"      content="pt_BR">
  <meta property="og:title"       content="GuildaByte | Criação de Sites e Sistemas para Pequenos Negócios">
  <meta property="og:description" content="Criamos sites, lojas virtuais e sistemas sob medida para pequenos negócios. Do setup ao suporte mensal.">
  <meta property="og:image"       content="{{ asset('assets/og-cover.jpg') }}">
  <meta property="og:url"         content="{{ url()->current() }}">
  <meta property="og:site_name"   content="GuildaByte">

  {{-- ===== SCHEMA.ORG (Ranqueamento Google Negócio Local) ===== --}}
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "ProfessionalService",
    "name": "GuildaByte",
    "description": "Agência especializada em criação de sites, lojas virtuais e sistemas para pequenos negócios como barbearias, fotógrafos, confeitarias e prestadores de serviço.",
    "url": "{{ config('app.url') }}",
    "serviceArea": { "@type": "Country", "name": "Brasil" },
    "priceRange": "R$1.000 - R$4.000",
    "hasOfferCatalog": {
      "@type": "OfferCatalog",
      "name": "Pacotes GuildaByte",
      "itemListElement": [
        { "@type": "Offer", "name": "Landing Page Start", "description": "Site profissional de uma página com SEO e hospedagem", "price": "1000", "priceCurrency": "BRL" },
        { "@type": "Offer", "name": "Sistema Core",        "description": "Sistema completo com painel administrativo, cadastros e automações", "price": "3000", "priceCurrency": "BRL" },
        { "@type": "Offer", "name": "Sistema Custom",      "description": "Solução personalizada com integrações avançadas e múltiplos módulos", "price": "4000", "priceCurrency": "BRL" }
      ]
    }
  }
  </script>

  {{-- ===== PERFORMANCE (Google PageSpeed) ===== --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  {{-- Só o subset de caracteres usados (latin) e apenas os pesos necessários --}}
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Pixelify+Sans:wght@700&family=Space+Grotesk:wght@400;700&display=swap&subset=latin" rel="stylesheet">

  {{-- CSS/JS via Vite (purge automático em build, só o que é usado) --}}
  @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/landing.js'])
  <link rel="stylesheet" href="{{ asset('style/design-system.css') }}">
</head>
<body>
  <div class="loader" aria-hidden="true">
    <img class="loader-mage" src="{{ asset('assets/magoChorma.gif') }}" alt="">
    <div class="loader-logo">
      <span></span>
      <strong>GuildaByte</strong>
    </div>
    <div class="loader-track"><span></span></div>
  </div>
  <div class="pixel-grid" aria-hidden="true"></div>

  <header class="site-header">
    <a class="brand" href="#top" aria-label="GuildaByte inicio">
      <img class="brand-avatar"
           src="{{ asset('assets/HeaderTrans.png') }}"
           alt=""
           aria-hidden="true"
           width="40" height="40">
      <span class="brand-name">Guilda<span>Byte</span></span>
    </a>

    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="main-nav" aria-label="Abrir menu">
      <span></span>
      <span></span>
      <span></span>
    </button>

    <nav class="main-nav" id="main-nav" aria-label="Navegacao principal">
      <div class="nav-actions">
        <a class="nav-auth" href="{{ route('login') }}">Login</a>
        <a class="nav-cta" href="{{ route('register') }}">Cadastro</a>
      </div>
    </nav>
  </header>

  <main id="top">
    <section class="hero-shell">
      <div class="hero-content">
        <p class="eyebrow"><span></span> A software house do seu negocio</p>
        <h1>Sistemas que dao lucro.</h1>
        <p class="hero-copy">
          Criamos plataformas de vendas, paineis administrativos e sistemas sob medida para a sua empresa parar de perder tempo com tarefas manuais e focar no que importa: crescer.
        </p>

        <div class="hero-actions">
          <a class="btn btn-primary" href="#planos">Escolher pacote</a>
          <a class="btn btn-secondary" href="#workflow">Ver fluxo</a>
        </div>

        <div class="hero-metrics" aria-label="Resumo dos servicos">
          <article>
            <strong class="numeric">15 dias</strong>
            <span>Landing + vitrine</span>
          </article>
          <article>
            <strong class="numeric">R$ 150</strong>
            <span>Manutencao mensal</span>
          </article>
          <article>
            <strong>Snapshot</strong>
            <span>Valor acordado nao muda sozinho</span>
          </article>
        </div>
      </div>

      <aside class="showcase-panel" aria-label="Preview visual GuildaByte">
        <div class="panel-topline">
          <span class="status-dot"></span>
          <span>Guilda OS</span>
          <strong>online</strong>
        </div>

        <div class="device-card">
          <div class="screen-header">
            <span>Projeto ativo</span>
            <strong class="numeric" id="preview-price">{{ $defaultPlan['price'] }}</strong>
          </div>

          <div class="quest-card">
            <span class="quest-icon">Q</span>
            <div>
              <strong id="preview-title">{{ $defaultPlan['title'] }}</strong>
              <p id="preview-description">{{ $defaultPlan['description'] }}</p>
            </div>
          </div>

          <div class="progress-wrap">
            <div class="progress-label">
              <span id="preview-stage">Desenvolvimento</span>
              <strong class="numeric" id="preview-progress">62%</strong>
            </div>
            <div class="progress-track">
              <span id="progress-bar"></span>
            </div>
          </div>

          <div class="mini-board" aria-label="Quadro de producao">
            <button class="board-card" type="button" data-stage="Planejamento" data-progress="28">
              <span class="numeric">01</span>
              Briefing
            </button>
            <button class="board-card is-active" type="button" data-stage="Desenvolvimento" data-progress="62">
              <span class="numeric">02</span>
              Build
            </button>
            <button class="board-card" type="button" data-stage="Homologacao" data-progress="86">
              <span class="numeric">03</span>
              Testes
            </button>
          </div>
        </div>
      </aside>
    </section>

    <section class="ticker" aria-label="Servicos GuildaByte">
      <div class="ticker-inner">
        @foreach(array_merge($ticker, $ticker) as $item)
          <span>{{ $item }}</span>
        @endforeach
      </div>
    </section>

    <section class="section-band" id="solucoes">
      <div class="section-heading">
        <p class="eyebrow"><span></span> O que resolvemos</p>
        <h2>Sua operacao esta um caos? Nos automatizamos.</h2>
      </div>

      <div class="feature-grid">
        @foreach($features as $feature)
          <article class="feature-card">
            <img class="pixel-asset"
                 src="{{ asset('assets/' . $feature['icon']) }}"
                 alt="Ícone: {{ $feature['title'] }}"
                 width="48" height="48"
                 loading="lazy"
                 decoding="async">
            <h3>{{ $feature['title'] }}</h3>
            <p>{{ $feature['text'] }}</p>
          </article>
        @endforeach
      </div>
    </section>

    <section class="niche-section" id="nichos">
      <div class="section-heading">
        <p class="eyebrow"><span></span> Para quem e</p>
        <h2>Projetado para quem precisa escalar, independente do nicho.</h2>
      </div>

      <div class="niche-grid">
        @foreach($niches as $niche)
          <article class="niche-card">
            <img src="{{ asset('assets/' . $niche['icon']) }}"
                 alt="Criação de site e sistema para {{ $niche['title'] }}"
                 width="40" height="40"
                 loading="lazy"
                 decoding="async">
            <strong>{{ $niche['title'] }}</strong>
            <p>{{ $niche['text'] }}</p>
          </article>
        @endforeach
      </div>
    </section>

    <section class="plans-section" id="planos">
      <div class="section-heading">
        <p class="eyebrow"><span></span> Planos iniciais</p>
        <h2>Escolha o nivel da sua primeira quest.</h2>
      </div>

      <div class="plans-grid" role="list">
        @foreach($plans as $key => $plan)
          <article class="plan-card{{ $key === 'core' ? ' is-featured' : '' }}" role="button" tabindex="0" data-plan-card="{{ $key }}" aria-pressed="{{ $key === 'core' ? 'true' : 'false' }}">
            @if(!empty($plan['badge']))
              <div class="badge">{{ $plan['badge'] }}</div>
            @endif
            <div class="plan-head">
              <span>{{ $plan['name'] }}</span>
              <strong class="numeric">{{ $plan['price'] }}</strong>
            </div>
            <p>{{ $plan['summary'] }}</p>
            <ul>
              @foreach($plan['items'] as $item)
                <li>{{ $item }}</li>
              @endforeach
            </ul>
            <button class="plan-select" type="button" data-plan="{{ $key }}">Quero esse</button>
          </article>
        @endforeach
      </div>
    </section>

    <section class="projects-section" id="projetos">
      <div class="section-heading">
        <p class="eyebrow"><span></span> Projetos reais</p>
        <h2>Casos reais de negocios que transformamos.</h2>
      </div>

      <div class="project-grid">
        @foreach($projects as $key => $project)
          <article class="project-card" data-project-card="{{ $key }}" tabindex="0" role="button" aria-label="Ver projeto {{ $project['name'] }}">
            <div class="project-titlebar">
              <span class="project-dot"></span>
              <span class="project-dot"></span>
              <span class="project-dot"></span>
              <strong>{{ $project['title'] }}</strong>
              <span class="project-arrow">&gt;</span>
            </div>
            <div class="project-preview {{ $project['preview'] }}" aria-hidden="true">
              @if($key === 'chokko')
                <div class="mock-phone">
                  <span></span>
                  <div class="mock-product"></div>
                  <div class="mock-line w1"></div>
                  <div class="mock-line w2"></div>
                  <div class="mock-cart"></div>
                </div>
                <div class="mock-admin">
                  <span></span>
                  <div></div>
                  <div></div>
                  <div></div>
                </div>
              @else
                <div class="mock-site">
                  <strong>ARS</strong>
                  <span></span>
                  <span></span>
                </div>
                <div class="mock-gallery">
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
              @endif
            </div>
            <div class="project-body">
              <h3>{{ $project['name'] }}</h3>
              <p>{{ $project['summary'] }}</p>
              <div class="project-tags">
                @foreach($project['tags'] as $tag)
                  <span>{{ $tag }}</span>
                @endforeach
              </div>
              <button class="project-button" type="button" data-project-open="{{ $key }}">Ver projeto</button>
            </div>
          </article>
        @endforeach
      </div>
    </section>

    <section class="workflow-section" id="workflow">
      <div class="section-heading">
        <p class="eyebrow"><span></span> Como funciona</p>
        <h2>Do pedido ate a entrega, tudo fica rastreavel.</h2>
      </div>

      <div class="timeline">
        @foreach($timeline as $index => $step)
          <article>
            <span class="numeric">{{ $index + 1 }}</span>
            <h3>{{ $step['title'] }}</h3>
            <p>{{ $step['text'] }}</p>
          </article>
        @endforeach
      </div>
    </section>

    <section class="briefing-section" id="briefing">
      <div class="briefing-panel">
        <div class="briefing-copy">
          <p class="eyebrow"><span></span> Abrir pedido</p>
          <h2>Escolha um pacote e continue pelo sistema.</h2>
          <p>
            O pacote escolhido fica salvo durante cadastro ou login. Depois disso, o pedido nasce dentro
            do painel com historico, status, proposta formal e notificacoes.
          </p>

          <div class="selected-plan" aria-live="polite">
            <span>Plano selecionado</span>
            <strong id="selected-plan-name">{{ $defaultPlan['label'] }}</strong>
            <small id="selected-plan-note">{{ $defaultPlan['note'] }}</small>
          </div>
        </div>

        <div class="briefing-form access-panel" id="briefing-form">
          <div class="flow-step">
            <span class="numeric">01</span>
            <strong>Escolha o pacote</strong>
            <p>Start, Core ou Custom ficam salvos no navegador.</p>
          </div>
          <div class="flow-step">
            <span class="numeric">02</span>
            <strong>Crie sua conta</strong>
            <p>O sistema carrega o pacote escolhido depois do cadastro.</p>
          </div>
          <div class="flow-step">
            <span class="numeric">03</span>
            <strong>Abra o pedido</strong>
            <p>Briefing, referencias, proposta e status ficam registrados.</p>
          </div>
          <div class="form-row">
            <a class="btn btn-primary form-submit" href="{{ route('register') }}" data-auth-entry="register">Criar conta</a>
            <a class="btn btn-secondary form-submit" href="{{ route('login') }}" data-auth-entry="login">Ja tenho login</a>
          </div>
          <p class="form-status" id="form-status" role="status"></p>
        </div>
      </div>
    </section>
  </main>

  <div class="project-modal" id="project-modal" aria-hidden="true">
    <div class="modal-backdrop" data-project-close></div>
    <section class="project-window" role="dialog" aria-modal="true" aria-labelledby="project-modal-title">
      <div class="project-titlebar">
        <span class="project-dot"></span>
        <span class="project-dot"></span>
        <span class="project-dot"></span>
        <strong id="project-modal-title">projeto.exe</strong>
        <button class="project-close" type="button" data-project-close aria-label="Fechar">X</button>
      </div>
      <div class="project-stage" id="project-stage"></div>
      <div class="project-controls">
        <button type="button" class="project-nav" id="project-prev" aria-label="Slide anterior">&lt;</button>
        <div class="project-dots" id="project-dots"></div>
        <button type="button" class="project-nav" id="project-next" aria-label="Proximo slide">&gt;</button>
      </div>
      <div class="project-caption">
        <strong id="project-slide-title"></strong>
        <p id="project-slide-text"></p>
      </div>
    </section>
  </div>

  <div class="auth-choice-modal" id="auth-choice-modal" aria-hidden="true">
    <div class="modal-backdrop" data-auth-choice-close></div>
    <section class="auth-choice-window" role="dialog" aria-modal="true" aria-labelledby="auth-choice-title">
      <div class="project-titlebar">
        <span class="project-dot"></span>
        <span class="project-dot"></span>
        <span class="project-dot"></span>
        <strong>continuar.exe</strong>
        <button class="project-close" type="button" data-auth-choice-close aria-label="Fechar">X</button>
      </div>
      <div class="auth-choice-body">
        <h2 id="auth-choice-title">Voce ja tem conta?</h2>
        <p>O pacote escolhido vai ficar salvo e sera carregado no novo pedido depois do acesso.</p>
        <div class="auth-choice-plan" id="auth-choice-plan"></div>
        <div class="auth-choice-actions">
          <button class="btn btn-secondary" type="button" data-auth-choice-login>Sim, fazer login</button>
          <button class="btn btn-primary" type="button" data-auth-choice-register>Nao, criar conta</button>
        </div>
      </div>
    </section>
  </div>

  <footer class="site-footer">
    <span>GuildaByte</span>
    <p>Sistemas bonitos, uteis e preparados para pequenos negocios crescerem.</p>
    <div class="footer-contact" aria-label="Contato principal">
      <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>
      <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener">{{ $supportPhoneDisplay }}</a>
    </div>
  </footer>

  <script type="application/json" id="guildabyte-content">
    {!! json_encode([
        'plans'    => $plans,
        'projects' => $projects,
        'routes'   => [
            'login'    => route('login'),
            'register' => route('register'),
        ],
    ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) !!}
  </script>
</body>
</html>



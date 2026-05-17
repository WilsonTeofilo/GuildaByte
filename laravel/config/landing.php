<?php

return [
    'plans' => [
        'start' => [
            'name'    => 'Start',
            'title'   => 'Landing + Vitrine',
            'label'   => 'Start — Landing + Vitrine',
            'note'    => 'Presença digital com vitrine e CTA para WhatsApp.',
            'price'   => 'R$ 1.000',
            'summary' => 'Presença digital com vitrine simples e CTA direto para WhatsApp.',
            'description' => 'Landing page completa com vitrine de produtos e link direto para WhatsApp.',
            'badge'   => '',
            'items'   => [
                'Landing page profissional',
                'Vitrine de produtos/serviços',
                'CTA para WhatsApp',
                'Responsividade completa',
                'Copy comercial básica',
            ],
        ],
        'core' => [
            'name'    => 'Core',
            'title'   => 'Sistema Completo',
            'label'   => 'Core — Sistema Completo',
            'note'    => 'Login, painel, pedidos/agendamentos, status e dashboard.',
            'price'   => 'R$ 3.000',
            'summary' => 'Sistema completo com login, painel admin, pedidos e dashboard.',
            'description' => 'Sistema com login, painel administrativo, pedidos/agendamentos e dashboard.',
            'badge'   => 'MAIS PEDIDO',
            'items'   => [
                'Login e cadastro',
                'Painel administrativo',
                'Pedidos ou agendamentos',
                'Status em tempo real',
                'Integração WhatsApp',
                'Dashboard simples',
            ],
        ],
        'custom' => [
            'name'    => 'Custom',
            'title'   => 'Sistema Personalizado',
            'label'   => 'Custom — Sistema Personalizado',
            'note'    => 'Tudo do Core + personalização visual, regras e módulos extras.',
            'price'   => 'R$ 4.000+',
            'summary' => 'Sistema sob medida com cores, regras e funcionalidades específicas.',
            'description' => 'Sistema personalizado com design exclusivo e funcionalidades sob medida.',
            'badge'   => '',
            'items'   => [
                'Tudo do pacote Core',
                'Cores e identidade personalizadas',
                'Regras específicas do negócio',
                'Mais telas e fluxos',
                'Refinamento completo',
            ],
        ],
    ],

    'features' => [
        ['icon' => 'landing.svg',    'title' => 'Landing pages',           'text' => 'Presença digital com vitrine, galeria e CTA direto para WhatsApp.'],
        ['icon' => 'dashboard.svg',  'title' => 'Painéis admin',           'text' => 'Dashboard visual para controlar pedidos, agendamentos e clientes.'],
        ['icon' => 'status.svg',     'title' => 'Status em tempo real',    'text' => 'Seu cliente acompanha cada etapa do pedido sem precisar perguntar.'],
        ['icon' => 'calendar.svg',   'title' => 'Agendamentos',            'text' => 'Agenda online integrada com notificação automática.'],
        ['icon' => 'whatsapp.svg',   'title' => 'WhatsApp integrado',      'text' => 'Botão de contato, notificações e confirmações via WhatsApp.'],
        ['icon' => 'finance.svg',    'title' => 'Controle financeiro',     'text' => 'Visualize entradas, saídas e relatórios de forma simples.'],
    ],

    'niches' => [
        ['icon' => 'barber.svg',       'title' => 'Barbearias',       'text' => 'Agendamento, fila e painel para gerenciar clientes.'],
        ['icon' => 'photographer.svg', 'title' => 'Fotógrafos',       'text' => 'Portfólio, galeria e entrega digital de álbuns.'],
        ['icon' => 'tattoo.svg',       'title' => 'Tatuadores',       'text' => 'Portfólio, orçamento e agendamento de sessões.'],
        ['icon' => 'beauty.svg',       'title' => 'Estética',         'text' => 'Agenda, procedimentos e controle de clientes.'],
        ['icon' => 'clothing.svg',     'title' => 'Marcas de roupa',  'text' => 'Vitrine online, catálogo e pedidos via WhatsApp.'],
        ['icon' => 'shop.svg',         'title' => 'Lojas',            'text' => 'Catálogo de produtos, pedidos e controle de estoque simples.'],
        ['icon' => 'restaurant.svg',   'title' => 'Restaurantes',     'text' => 'Cardápio digital, pedidos e status para delivery.'],
        ['icon' => 'business.svg',     'title' => 'Autônomos',        'text' => 'Sistema simples para organizar serviços e clientes.'],
    ],

    'timeline' => [
        ['title' => 'Escolha o pacote',   'text' => 'Start, Core ou Custom. Cada um com escopo, prazo e valor definidos.'],
        ['title' => 'Envie seu briefing',  'text' => 'Conte sobre seu negócio, o que precisa e quais referências tem.'],
        ['title' => 'Receba a proposta',   'text' => 'A GuildaByte analisa e envia uma proposta formal com escopo e valor.'],
        ['title' => 'Acompanhe tudo',      'text' => 'Status, timeline, aprovações e suporte — tudo no seu painel.'],
    ],

    'projects' => [
        'chokko' => [
            'name'    => 'Chokko Melt',
            'title'   => 'CHOKKO_MELT.EXE',
            'summary' => 'Sistema de pedidos para doceria artesanal com painel admin, cardápio digital e status.',
            'preview' => 'project-preview-chokko',
            'tags'    => ['PHP', 'Sistema', 'Pedidos', 'Admin'],
            'slides'  => [
                ['title' => 'Cardápio digital',       'text' => 'Cliente navega e monta pedido direto pelo celular.'],
                ['title' => 'Painel administrativo',   'text' => 'Controle de pedidos, status e produção em tempo real.'],
                ['title' => 'Status para cliente',     'text' => 'O cliente acompanha cada etapa sem precisar perguntar.'],
            ],
        ],
        'ars' => [
            'name'    => 'ARS Fotografia',
            'title'   => 'ARS_FOTO.EXE',
            'summary' => 'Landing page com portfólio, galeria responsiva e CTA para WhatsApp.',
            'preview' => 'project-preview-ars',
            'tags'    => ['Landing', 'Portfólio', 'Galeria'],
            'slides'  => [
                ['title' => 'Portfólio visual',        'text' => 'Galeria responsiva com grid de fotos e preview.'],
                ['title' => 'Contato direto',          'text' => 'CTA para WhatsApp com mensagem pré-formatada.'],
                ['title' => 'Mobile first',            'text' => 'Toda a experiência pensada para o celular primeiro.'],
            ],
        ],
    ],

    'ticker' => [
        'LANDING PAGES', 'SISTEMAS WEB', 'PAINÉIS ADMIN', 'AGENDAMENTOS',
        'PEDIDOS ONLINE', 'STATUS', 'WHATSAPP', 'DASHBOARD', 'AUTOMAÇÃO',
        'SUPORTE', 'MANUTENÇÃO', 'RESPONSIVO',
    ],
];

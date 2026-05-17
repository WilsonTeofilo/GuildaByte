<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Área do Cliente') | GuildaByte</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Pixelify+Sans:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite CSS/JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Custom CSS (Keep the original UI/UX vibes) -->
    <link rel="stylesheet" href="{{ asset('style/design-system.css') }}?v=1.0">
    <style>
        /* Mobile-first basic resets and variables for client portal */
        :root {
            --gb-bg: #050507;
            --gb-panel: #101018;
            --gb-panel-light: #13131c;
            --gb-border: #26215c;
            --gb-green: #92ffcb;
            --gb-purple: #7f77dd;
            --gb-text: #f6f4ff;
            --gb-muted: #aaa4bc;
            --gb-danger: #ff7893;
        }
        body.client-portal {
            background-color: var(--gb-bg);
            color: var(--gb-text);
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .pixel-font {
            font-family: 'Pixelify Sans', cursive;
        }
        .gb-card {
            background-color: var(--gb-panel);
            border: 1px solid var(--gb-border);
            border-radius: 16px;
        }
        .gb-btn-primary {
            background-color: var(--gb-green);
            color: #050507;
            font-weight: 700;
            border-radius: 12px;
            transition: all 0.2s ease;
        }
        .gb-btn-primary:hover {
            background-color: #b8ffdd;
            transform: translateY(-1px);
        }
        /* Mobile Navigation Bar (Bottom) */
        @media (max-width: 768px) {
            .mobile-nav-spacing { padding-bottom: 80px; }
        }
    </style>
    @stack('styles')
</head>
<body class="client-portal flex h-screen overflow-hidden">

    <!-- Desktop Sidebar (Hidden on Mobile) -->
    <aside class="hidden md:flex flex-col w-64 border-r border-[#26215c] bg-[#0A0A10] h-full z-20">
        <div class="p-6">
            <a href="{{ route('client.dashboard') }}" class="flex items-center gap-3 decoration-transparent">
                <img src="{{ asset('assets/HeaderTrans.webp') }}" alt="GuildaByte Avatar" class="w-10 h-10 rounded border border-[#26215c]" width="40" height="40" loading="lazy" decoding="async">
                <div>
                    <strong class="text-xl font-bold block pixel-font text-[#f6f4ff]">GuildaByte</strong>
                    <span class="text-[#7f77dd] text-xs font-semibold uppercase tracking-wider">Cliente</span>
                </div>
            </a>
        </div>
        
        <nav class="flex-1 px-4 space-y-2 mt-4 overflow-y-auto">
            <a href="{{ route('client.dashboard') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('client.dashboard') ? 'bg-[#92ffcb]/10 text-[#92ffcb] border border-[#92ffcb]/30' : 'text-[#aaa4bc] hover:bg-white/5 hover:text-white' }} font-bold transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            <a href="#" class="flex items-center gap-3 p-3 rounded-xl text-[#aaa4bc] hover:bg-white/5 hover:text-white font-bold transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Meus Projetos
            </a>
            <a href="{{ route('client.wizard') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('client.wizard') ? 'bg-[#7f77dd]/20 text-[#afa9ec] border border-[#7f77dd]/30' : 'text-[#aaa4bc] hover:bg-white/5 hover:text-white' }} font-bold transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Novo Pedido
            </a>
            <a href="#" class="flex items-center gap-3 p-3 rounded-xl text-[#aaa4bc] hover:bg-white/5 hover:text-white font-bold transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Suporte
            </a>
        </nav>
        
        <div class="p-4 border-t border-[#26215c] bg-[#101018]">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-8 h-8 rounded-full bg-[#26215c] flex items-center justify-center text-xs font-bold">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <div class="overflow-hidden">
                    <div class="text-sm font-bold truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-[#aaa4bc] truncate">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-center p-2 rounded-lg text-[#ff7893] hover:bg-[#ff7893]/10 font-bold transition-colors text-sm">
                    Sair do Sistema
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full w-full overflow-hidden relative">
        <!-- Mobile Header -->
        <header class="md:hidden flex items-center justify-between p-4 border-b border-[#26215c] bg-[#0A0A10] z-20">
            <div class="flex items-center gap-2">
                <img src="{{ asset('assets/HeaderTrans.webp') }}" alt="Logo GuildaByte" class="w-8 h-8 rounded" width="32" height="32" loading="lazy" decoding="async">
                <strong class="text-lg font-bold pixel-font">GuildaByte</strong>
            </div>
            <div class="w-8 h-8 rounded-full bg-[#26215c] flex items-center justify-center text-xs font-bold">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
        </header>

        <!-- Dynamic Content -->
        <div class="flex-1 overflow-y-auto p-4 md:p-8 mobile-nav-spacing">
            @yield('content')
        </div>

        <!-- Mobile Bottom Navigation -->
        <nav class="md:hidden fixed bottom-0 left-0 w-full bg-[#0A0A10] border-t border-[#26215c] flex items-center justify-around p-2 pb-safe z-30">
            <a href="{{ route('client.dashboard') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('client.dashboard') ? 'text-[#92ffcb]' : 'text-[#aaa4bc]' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="text-[10px] font-bold">Início</span>
            </a>
            <a href="#" class="flex flex-col items-center p-2 text-[#aaa4bc]">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <span class="text-[10px] font-bold">Projetos</span>
            </a>
            <!-- Main Floating Action Button for Mobile -->
            <a href="#" class="relative -top-4 flex flex-col items-center justify-center w-14 h-14 rounded-full bg-[#92ffcb] text-[#050507] shadow-[0_4px_20px_rgba(146,255,203,0.4)] border-4 border-[#050507]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </a>
            <a href="#" class="flex flex-col items-center p-2 text-[#aaa4bc]">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="text-[10px] font-bold">Suporte</span>
            </a>
            <a href="#" class="flex flex-col items-center p-2 text-[#aaa4bc]">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span class="text-[10px] font-bold">Perfil</span>
            </a>
        </nav>
    </main>

    @stack('scripts')
</body>
</html>

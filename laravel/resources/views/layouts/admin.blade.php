<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Área Admin') | GuildaByte</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Pixelify+Sans:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite CSS/JS -->
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="client-portal flex h-screen overflow-hidden">

    <!-- Desktop Sidebar (Hidden on Mobile) -->
    <aside class="hidden md:flex flex-col w-64 border-r border-[#26215c] bg-[#0A0A10] h-full z-20">
        <div class="p-6">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 decoration-transparent">
                <img src="{{ asset('assets/HeaderTrans.webp') }}" alt="GuildaByte Avatar" class="w-10 h-10 rounded border border-[#26215c]" width="40" height="40" loading="lazy" decoding="async">
                <div>
                    <strong class="text-xl font-bold block pixel-font text-[#f6f4ff]">GuildaByte</strong>
                    <span class="text-[#ff7893] text-xs font-semibold uppercase tracking-wider">ROOT ADMIN</span>
                </div>
            </a>
        </div>
        
        <nav class="flex-1 px-4 space-y-1 mt-4 overflow-y-auto">

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-[#ff7893]/10 text-[#ff7893] border border-[#ff7893]/30' : 'text-[#aaa4bc] hover:bg-white/5 hover:text-white' }} font-bold transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>

            {{-- Projetos --}}
            <a href="{{ route('admin.projects.index') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('admin.projects.*') ? 'bg-[#7f77dd]/20 text-[#afa9ec] border border-[#7f77dd]/30' : 'text-[#aaa4bc] hover:bg-white/5 hover:text-white' }} font-bold transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Projetos
            </a>

            {{-- Separador FINANCEIRO --}}
            <div class="px-3 pt-3 pb-1">
                <span class="text-[10px] font-bold text-[#555] uppercase tracking-widest">Financeiro</span>
            </div>

            {{-- Pacotes --}}
            <a href="{{ route('admin.packages.index') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('admin.packages.*') ? 'bg-[#92ffcb]/10 text-[#92ffcb] border border-[#92ffcb]/30' : 'text-[#aaa4bc] hover:bg-white/5 hover:text-white' }} font-bold transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Pacotes
            </a>

            {{-- Promoções --}}
            <a href="{{ route('admin.promotions.index') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('admin.promotions.*') ? 'bg-[#ffe135]/10 text-[#ffe135] border border-[#ffe135]/30' : 'text-[#aaa4bc] hover:bg-white/5 hover:text-white' }} font-bold transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Promoções
            </a>

            {{-- Separador EM BREVE --}}
            <div class="px-3 pt-3 pb-1">
                <span class="text-[10px] font-bold text-[#333] uppercase tracking-widest">Em Breve</span>
            </div>

            {{-- Clientes (futuro) --}}
            <span class="flex items-center gap-3 p-3 rounded-xl text-[#333] cursor-not-allowed font-bold">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Clientes
                <span class="ml-auto text-[9px] border border-[#333] px-1 py-0.5">SOON</span>
            </span>

            {{-- Suporte (futuro) --}}
            <span class="flex items-center gap-3 p-3 rounded-xl text-[#333] cursor-not-allowed font-bold">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Suporte
                <span class="ml-auto text-[9px] border border-[#333] px-1 py-0.5">SOON</span>
            </span>

        </nav>
        
        <div class="p-4 border-t border-[#26215c] bg-[#101018]">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-8 h-8 rounded-full bg-[#ff7893] text-[#0a0a10] flex items-center justify-center text-xs font-bold">{{ substr(Auth::user()->name, 0, 1) }}</div>
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
                <strong class="text-lg font-bold pixel-font text-[#ff7893]">ADMIN</strong>
            </div>
            <div class="w-8 h-8 rounded-full bg-[#ff7893] text-[#0a0a10] flex items-center justify-center text-xs font-bold">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
        </header>

        <!-- Dynamic Content -->
        <div class="flex-1 overflow-y-auto p-4 md:p-8 mobile-nav-spacing">
            @yield('content')
        </div>

        <!-- Mobile Bottom Navigation -->
        <nav class="md:hidden fixed bottom-0 left-0 w-full bg-[#0A0A10] border-t border-[#26215c] flex items-center justify-around p-2 pb-safe z-30">
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('admin.dashboard') ? 'text-[#ff7893]' : 'text-[#aaa4bc]' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="text-[10px] font-bold">Início</span>
            </a>
            <a href="{{ route('admin.projects.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('admin.projects.*') ? 'text-[#ff7893]' : 'text-[#aaa4bc]' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <span class="text-[10px] font-bold">Projetos</span>
            </a>
            <a href="{{ route('admin.packages.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('admin.packages.*') ? 'text-[#92ffcb]' : 'text-[#aaa4bc]' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <span class="text-[10px] font-bold">Pacotes</span>
            </a>
            <a href="{{ route('admin.promotions.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('admin.promotions.*') ? 'text-[#ffe135]' : 'text-[#aaa4bc]' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                <span class="text-[10px] font-bold">Promoções</span>
            </a>
        </nav>
    </main>

    @stack('scripts')
</body>
</html>

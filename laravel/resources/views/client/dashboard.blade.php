@extends('layouts.client')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <span class="text-[#92ffcb] text-xs font-black uppercase tracking-wider">Bem-vindo(a) à Guilda</span>
    <h1 class="text-3xl md:text-5xl font-bold mt-2 pixel-font">Olá, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
    <p class="text-[#aaa4bc] mt-2 md:mt-4 max-w-2xl text-sm md:text-lg">
        Acompanhe seus projetos, pagamentos e atualizações em tempo real. Sem burocracia, do jeito que tem que ser.
    </p>
</div>

<!-- Grid de Resumo Rápido -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="gb-card p-5 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-24 h-24 bg-[#7f77dd] opacity-5 rounded-bl-full transform group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-[#aaa4bc] font-semibold text-sm">Projetos Ativos</h3>
            <div class="w-8 h-8 rounded bg-[#7f77dd]/20 flex items-center justify-center text-[#7f77dd]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
        </div>
        <div class="text-3xl font-bold pixel-font">1</div>
    </div>
    
    <div class="gb-card p-5 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-24 h-24 bg-[#ff7893] opacity-5 rounded-bl-full transform group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-[#aaa4bc] font-semibold text-sm">Faturas Pendentes</h3>
            <div class="w-8 h-8 rounded bg-[#ff7893]/20 flex items-center justify-center text-[#ff7893]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="text-3xl font-bold pixel-font">0</div>
    </div>
    
    <div class="gb-card p-5 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-24 h-24 bg-[#92ffcb] opacity-5 rounded-bl-full transform group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-[#aaa4bc] font-semibold text-sm">Tickets de Suporte</h3>
            <div class="w-8 h-8 rounded bg-[#92ffcb]/20 flex items-center justify-center text-[#92ffcb]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>
        <div class="text-3xl font-bold pixel-font">0</div>
    </div>
</div>

<!-- Destaque: Projeto Atual -->
<div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg md:text-xl font-bold">Projeto Atual</h2>
        <a href="#" class="text-sm font-bold text-[#7f77dd] hover:text-white transition-colors">Ver todos</a>
    </div>
    
    <div class="gb-card p-6 border-l-4 border-l-[#7f77dd]">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-2 py-1 rounded bg-[#7f77dd]/20 text-[#d9d5ff] text-[10px] font-black uppercase tracking-wider">
                        Em Desenvolvimento
                    </span>
                    <span class="text-xs font-semibold text-[#aaa4bc]">Atualizado há 2 dias</span>
                </div>
                <h3 class="text-xl md:text-2xl font-bold text-white mb-1">Guilda Custom - Barbearia</h3>
                <p class="text-sm text-[#aaa4bc]">Sistema de agendamento online e painel de gestão de comissões.</p>
            </div>
            <div class="flex md:flex-col items-center md:items-end gap-3 w-full md:w-auto">
                <div class="flex-1 md:w-48">
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-[#92ffcb]">Progresso</span>
                        <span class="text-white">35%</span>
                    </div>
                    <div class="w-full h-2 bg-[#101018] rounded-full overflow-hidden border border-[#26215c]">
                        <div class="h-full bg-gradient-to-r from-[#7f77dd] to-[#92ffcb] rounded-full" style="width: 35%"></div>
                    </div>
                </div>
                <a href="#" class="gb-btn-primary px-4 py-2 text-sm whitespace-nowrap">Ver Detalhes</a>
            </div>
        </div>
    </div>
</div>

<!-- Seção: Ações Rápidas (Aparece mais no Desktop) -->
<div class="mt-8 hidden md:block">
    <h2 class="text-lg md:text-xl font-bold mb-4">Ações Rápidas</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="#" class="gb-card p-4 hover:bg-[#13131c] transition-colors flex flex-col items-center justify-center text-center gap-3 group">
            <div class="w-12 h-12 rounded-full bg-[#26215c] flex items-center justify-center text-[#7f77dd] group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </div>
            <span class="font-bold text-sm">Novo Pedido</span>
        </a>
        <a href="#" class="gb-card p-4 hover:bg-[#13131c] transition-colors flex flex-col items-center justify-center text-center gap-3 group">
            <div class="w-12 h-12 rounded-full bg-[#26215c] flex items-center justify-center text-[#7f77dd] group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
            </div>
            <span class="font-bold text-sm">Mensagens</span>
        </a>
        <a href="#" class="gb-card p-4 hover:bg-[#13131c] transition-colors flex flex-col items-center justify-center text-center gap-3 group">
            <div class="w-12 h-12 rounded-full bg-[#26215c] flex items-center justify-center text-[#7f77dd] group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <span class="font-bold text-sm">Arquivos</span>
        </a>
        <a href="#" class="gb-card p-4 hover:bg-[#13131c] transition-colors flex flex-col items-center justify-center text-center gap-3 group">
            <div class="w-12 h-12 rounded-full bg-[#26215c] flex items-center justify-center text-[#7f77dd] group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            </div>
            <span class="font-bold text-sm">Faturas</span>
        </a>
    </div>
</div>
@endsection

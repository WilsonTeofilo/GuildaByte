@extends('layouts.client')

@section('title', 'Detalhe do Projeto')

@push('styles')
    <link rel="stylesheet" href="{{ asset('style/projects.css') }}?v=1.0">
@endpush

@section('content')

<div class="rpg-page-header">
  <h1>{{ $project->name }}</h1>
  <span class="rpg-status-badge">{{ $project->status->name ?? 'Aguardando' }}</span>
</div>

<div class="rpg-tabs">
  <button class="rpg-tab active" onclick="openTab(event, 'tab-timeline')">TIMELINE</button>
  <button class="rpg-tab" onclick="openTab(event, 'tab-proposta')">PROPOSTA</button>
  <button class="rpg-tab" onclick="openTab(event, 'tab-arquivos')">ARQUIVOS</button>
  <button class="rpg-tab" onclick="openTab(event, 'tab-mensagens')">MENSAGENS</button>
</div>

<div id="tab-timeline" class="rpg-tab-content active">
  <ul class="timeline">
    <li class="timeline-item completed">
      <span class="timeline-date">{{ $project->created_at->format('d/m/Y H:i') }}</span>
      <div class="timeline-title">Pedido Criado</div>
      <p class="text-sm" style="color: var(--gb-muted);">Você iniciou a missão selecionando o pacote base.</p>
    </li>
    <li class="timeline-item">
      <span class="timeline-date">Pendente</span>
      <div class="timeline-title">Briefing e Proposta</div>
      <p class="text-sm" style="color: var(--gb-muted);">Aguardando envio e aceite da proposta comercial formal.</p>
    </li>
    <li class="timeline-item">
      <span class="timeline-date">Em breve</span>
      <div class="timeline-title">Início do Desenvolvimento</div>
      <p class="text-sm" style="color: var(--gb-muted);">A Guilda começa a codificar a sua solução.</p>
    </li>
  </ul>
</div>

<div id="tab-proposta" class="rpg-tab-content">
  <h2 style="color: var(--gb-purple-light); margin-bottom: 1rem;">Documento de Proposta</h2>

  @if($project->contractAcceptances->isNotEmpty())
    {{-- Proposta já aceita --}}
    <div style="background: rgba(146,255,203,0.08); border: 1px solid var(--gb-green); border-radius: 8px; padding: 1.5rem; margin-bottom: 1rem;">
      <p style="color: var(--gb-green); font-family: 'Press Start 2P', monospace; font-size: 0.6rem; margin-bottom: 0.8rem;">✓ PROPOSTA ACEITA DIGITALMENTE</p>
      @php $acceptance = $project->contractAcceptances->first(); @endphp
      <div style="font-family: monospace; font-size: 0.8rem; color: var(--gb-muted); line-height: 2;">
        <div>Data: {{ $acceptance->accepted_at->format('d/m/Y \à\s H:i') }}</div>
        <div>IP: {{ $acceptance->ip_address }}</div>
        <div style="word-break: break-all;">Dispositivo: {{ Str::limit($acceptance->user_agent, 80) }}</div>
      </div>
    </div>
  @else
    {{-- Proposta pendente de aceite --}}
    <div style="background: #0a0a10; padding: 1.5rem; border: 1px solid var(--gb-border); border-radius: 8px; margin-bottom: 1.5rem;">
      <p style="color: var(--gb-muted); font-family: monospace; font-size: 0.85rem;">A proposta formal ainda está sendo preparada pela equipe.</p>
    </div>

    {{-- Botão de aceite — só mostra se projeto estiver em estado de aguardando aceite --}}
    @if($project->status === 'awaiting_acceptance')
      <div style="border: 2px solid var(--gb-green); border-radius: 8px; padding: 1.5rem; background: rgba(146,255,203,0.04);">
        <p style="font-size: 0.8rem; color: var(--gb-text); margin-bottom: 1rem;">
          Ao aceitar, você confirma que leu e concorda com os termos desta proposta.<br>
          <small style="color: var(--gb-muted);">Seu IP e informações de dispositivo serão registrados como prova de aceite digital.</small>
        </p>
        <form method="POST" action="{{ route('client.projects.accept', $project) }}" onsubmit="return confirm('Confirmar aceite digital? Esta ação é irreversível.')">
          @csrf
          <button type="submit" style="background: var(--gb-green); color: #0a0a10; border: none; padding: 0.8rem 2rem; font-family: 'Press Start 2P', monospace; font-size: 0.65rem; border-radius: 4px; cursor: pointer; transition: opacity 0.2s;">
            ACEITAR PROPOSTA DIGITALMENTE
          </button>
        </form>
      </div>
    @endif
  @endif
</div>

<div id="tab-arquivos" class="rpg-tab-content">
  <div class="placeholder-text">Inventário vazio. Nenhum arquivo enviado ainda.</div>
</div>

<div id="tab-mensagens" class="rpg-tab-content">
  <div class="placeholder-text">O chat da guilda está silencioso...</div>
</div>

@push('scripts')
    <script src="{{ asset('js/projects.js') }}?v=1.0"></script>
@endpush
@endsection

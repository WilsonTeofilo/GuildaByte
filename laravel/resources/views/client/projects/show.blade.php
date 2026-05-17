@extends('layouts.client')

@section('title', 'Detalhe do Projeto')

@push('styles')
    <link rel="stylesheet" href="{{ asset('style/projects.css') }}?v=1.0">
@endpush

@section('content')

<div class="rpg-page-header">
  <div>
    <h1>{{ $project->name }}</h1>
    <span class="rpg-status-badge">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
  </div>
  <div class="proj-header-actions">
    <a href="{{ route('projects.calls.index', $project) }}" class="adm-action-btn adm-btn--purple proj-action-btn" style="border:1px solid #7f77dd;">HISTORICO CALLS</a>
    <a href="{{ route('projects.chat.index', $project) }}" class="adm-action-btn adm-btn--purple proj-action-btn" style="border:1px solid #7f77dd;">CHAT</a>
    @if(in_array($project->status, ['in_progress', 'testing']))
      <a href="{{ route('projects.calls.create', $project) }}" class="adm-action-btn adm-btn--green proj-action-btn" style="border:1px solid #92ffcb;">AGENDAR CALL</a>
    @endif
  </div>
</div>

<div class="rpg-tabs">
  <button class="rpg-tab active" onclick="openTab(event, 'tab-timeline')">TIMELINE</button>
  <button class="rpg-tab" onclick="openTab(event, 'tab-proposta')">PROPOSTA</button>
  <button class="rpg-tab" onclick="openTab(event, 'tab-arquivos')">ARQUIVOS</button>
</div>

<div id="tab-timeline" class="rpg-tab-content active">
  <ul class="timeline">
    <li class="timeline-item completed">
      <span class="timeline-date">{{ $project->created_at->format('d/m/Y H:i') }}</span>
      <div class="timeline-title">Pedido Criado</div>
      <p class="text-sm proj-timeline-text">Voce iniciou a missao selecionando o pacote base.</p>
    </li>
    <li class="timeline-item">
      <span class="timeline-date">Pendente</span>
      <div class="timeline-title">Briefing e Proposta</div>
      <p class="text-sm proj-timeline-text">Aguardando envio e aceite da proposta comercial formal.</p>
    </li>
    <li class="timeline-item">
      <span class="timeline-date">Em breve</span>
      <div class="timeline-title">Inicio do Desenvolvimento</div>
      <p class="text-sm proj-timeline-text">A Guilda comeca a codificar a sua solucao.</p>
    </li>
  </ul>
</div>

<div id="tab-proposta" class="rpg-tab-content">
  <h2 class="proj-proposal-title">Documento de Proposta</h2>

  @if($project->contractAcceptances->isNotEmpty())
    <div class="proj-proposal-success">
      <p class="proj-proposal-success-title">&#10003; PROPOSTA ACEITA DIGITALMENTE</p>
      @php $acceptance = $project->contractAcceptances->first(); @endphp
      <div class="proj-proposal-success-body">
        <div>Data: {{ $acceptance->accepted_at->format('d/m/Y H:i') }}</div>
        <div>IP: {{ $acceptance->ip_address }}</div>
        <div class="proj-proposal-success-ua">Dispositivo: {{ Str::limit($acceptance->user_agent, 80) }}</div>
      </div>
    </div>
  @else
    <div class="proj-proposal-pending">
      <p class="proj-proposal-pending-text">A proposta formal ainda esta sendo preparada pela equipe.</p>
    </div>

    @if($project->status === 'awaiting_acceptance')
      <div class="proj-accept-box">
        <p class="proj-accept-text">
          Ao aceitar, voce confirma que leu e concorda com os termos desta proposta.<br>
          <small class="proj-accept-sub">Seu IP e informacoes de dispositivo serao registrados como prova de aceite digital.</small>
        </p>
        <form method="POST" action="{{ route('client.projects.accept', $project) }}" onsubmit="return confirm('Confirmar aceite digital? Esta acao e irreversivel.')">
          @csrf
          <button type="submit" class="proj-accept-btn">
            ACEITAR PROPOSTA DIGITALMENTE
          </button>
        </form>
      </div>
    @endif
  @endif

  @if($project->addendums && $project->addendums->isNotEmpty())
    <h3 class="proj-proposal-title" style="margin-top:2rem;">Aditivos de Escopo</h3>
    @foreach($project->addendums as $addendum)
      <div class="proj-addendum-box">
        <h4 class="proj-addendum-title">{{ $addendum->title }}</h4>
        <p class="proj-addendum-desc">{{ $addendum->description }}</p>
        <div class="proj-addendum-stats">
          <span style="color:var(--gb-danger);">+ R$ {{ number_format($addendum->additional_cost, 2, ',', '.') }}</span>
          <span style="color:var(--gb-danger);">+ {{ $addendum->additional_days }} dias</span>
          <span style="color:var(--gb-green);">Status: {{ strtoupper($addendum->status) }}</span>
        </div>
        @if($addendum->status === 'pending')
          <form method="POST" action="{{ route('client.addendums.accept', $addendum) }}" onsubmit="return confirm('Confirmar aceite digital do aditivo?')">
            @csrf
            <button type="submit" class="proj-addendum-btn">
              ACEITAR ADITIVO
            </button>
          </form>
        @else
          <div class="proj-addendum-log">
            Aceito em: {{ $addendum->accepted_at->format('d/m/Y H:i') }} | IP: {{ $addendum->ip_address }}
          </div>
        @endif
      </div>
    @endforeach
  @endif
</div>

<div id="tab-arquivos" class="rpg-tab-content">
  <div class="placeholder-text">Inventario vazio. Nenhum arquivo enviado ainda.</div>
</div>

@push('scripts')
    @vite(['resources/js/projects.js'])
@endpush
@endsection
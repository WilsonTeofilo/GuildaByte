@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')

<div class="adm-page-header">
  <h1 class="adm-title">⚔ CENTRO DE COMANDO</h1>
  <p class="adm-subtitle">Missão Control — GuildaByte Operations</p>
</div>

{{-- ── MÉTRICAS ── --}}
<div class="adm-metrics-grid">

  {{-- Novos Pedidos (badge piscante se > 0) --}}
  <a href="{{ route('admin.projects.index', ['status' => 'received']) }}" class="adm-metric-card adm-metric--red {{ $newOrdersCount > 0 ? 'adm-metric--pulse' : '' }}">
    <div class="adm-metric-icon">📥</div>
    <div class="adm-metric-value">{{ $newOrdersCount }}</div>
    <div class="adm-metric-label">NOVOS PEDIDOS</div>
    @if($newOrdersCount > 0)<div class="adm-metric-badge">AÇÃO NECESSÁRIA</div>@endif
  </a>

  {{-- Propostas sem aceite --}}
  <a href="{{ route('admin.projects.index', ['status' => 'proposal_sent']) }}" class="adm-metric-card adm-metric--yellow {{ $pendingProposals > 0 ? 'adm-metric--pulse-slow' : '' }}">
    <div class="adm-metric-icon">📋</div>
    <div class="adm-metric-value">{{ $pendingProposals }}</div>
    <div class="adm-metric-label">AGUARDANDO ACEITE</div>
    @if($pendingProposals > 0)<div class="adm-metric-badge adm-badge--yellow">PENDENTE</div>@endif
  </a>

  {{-- Receita do mês (paid) --}}
  <div class="adm-metric-card adm-metric--green">
    <div class="adm-metric-icon">💰</div>
    <div class="adm-metric-value adm-metric-value--small">R$ {{ number_format($monthlyRevenue, 2, ',', '.') }}</div>
    <div class="adm-metric-label">RECEITA CONFIRMADA</div>
    <div class="adm-metric-sub">{{ now()->format('M/Y') }}</div>
  </div>

  {{-- Projetos atrasados --}}
  <a href="{{ route('admin.projects.index', ['status' => 'in_progress']) }}" class="adm-metric-card {{ $overdueCount > 0 ? 'adm-metric--red adm-metric--pulse' : 'adm-metric--purple' }}">
    <div class="adm-metric-icon">⏰</div>
    <div class="adm-metric-value">{{ $overdueCount }}</div>
    <div class="adm-metric-label">PROJETOS ATRASADOS</div>
    @if($overdueCount > 0)<div class="adm-metric-badge">CRÍTICO</div>@endif
  </a>

  {{-- Total clientes --}}
  <div class="adm-metric-card adm-metric--purple">
    <div class="adm-metric-icon">👤</div>
    <div class="adm-metric-value">{{ $totalClients }}</div>
    <div class="adm-metric-label">CLIENTES ATIVOS</div>
  </div>

</div>

{{-- ── FEED DE ATIVIDADE ── --}}
<div class="adm-feed-section">
  <h2 class="adm-section-title">▶ FEED DE ATIVIDADE</h2>

  @if($activityFeed->isEmpty())
    <div class="adm-empty">Nenhuma atividade financeira registrada ainda.</div>
  @else
    <div class="adm-feed">
      @foreach($activityFeed as $event)
      <div class="adm-feed-item">
        <div class="adm-feed-type adm-feed-type--{{ $event->event_type }}">
          @php $icons = ['price_created' => '🆕', 'status_changed' => '🔄', 'manual_adjustment' => '✏️']; @endphp
          {{ $icons[$event->event_type] ?? '📌' }}
        </div>
        <div class="adm-feed-body">
          <div class="adm-feed-project">
            <a href="{{ route('admin.projects.show', $event->project_id) }}">
              {{ $event->project?->name ?? '#'.$event->project_id }}
            </a>
            <span class="adm-feed-client">— {{ $event->project?->client?->name ?? 'N/A' }}</span>
          </div>
          <div class="adm-feed-reason">{{ $event->reason }}</div>
          @if($event->new_value)
          <div class="adm-feed-value">R$ {{ number_format($event->new_value, 2, ',', '.') }}</div>
          @endif
        </div>
        <div class="adm-feed-time">{{ $event->created_at->diffForHumans() }}</div>
      </div>
      @endforeach
    </div>
  @endif
</div>

@endsection

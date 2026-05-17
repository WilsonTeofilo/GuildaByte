@extends('layouts.admin')
@section('title', 'Fila de Projetos')

@section('content')

<div class="adm-page-header">
  <h1 class="adm-title">📁 FILA DE PROJETOS</h1>
  <p class="adm-subtitle">FIFO — pedidos mais antigos são atendidos primeiro</p>
</div>

{{-- Filtros de Status --}}
<div class="adm-filter-bar">
  @php
    $statuses = [
      ''                  => ['label' => 'TODOS',      'color' => 'purple'],
      'received'          => ['label' => 'RECEBIDOS',  'color' => 'red'],
      'in_analysis'       => ['label' => 'EM ANÁLISE', 'color' => 'yellow'],
      'proposal_sent'     => ['label' => 'PROPOSTA',   'color' => 'yellow'],
      'proposal_accepted' => ['label' => 'ACEITOS',    'color' => 'green'],
      'in_progress'       => ['label' => 'EM DEV',     'color' => 'green'],
      'delivered'         => ['label' => 'ENTREGUES',  'color' => 'green'],
    ];
  @endphp

  @foreach($statuses as $val => $meta)
  <a href="{{ route('admin.projects.index', array_filter(['status' => $val ?: null, 'search' => $search])) }}"
     class="adm-filter-btn adm-filter-btn--{{ $meta['color'] }} {{ $status === ($val ?: null) || ($val === '' && !$status) ? 'adm-filter-btn--active' : '' }}">
    {{ $meta['label'] }}
    @if(isset($statusCounts[$val]))<span class="adm-filter-count">{{ $statusCounts[$val] }}</span>@endif
  </a>
  @endforeach
</div>

{{-- Busca --}}
<form method="GET" action="{{ route('admin.projects.index') }}" class="adm-search-bar">
  @if($status)<input type="hidden" name="status" value="{{ $status }}">@endif
  <input type="text" name="search" value="{{ $search }}" class="adm-search-input" placeholder="Buscar por projeto ou cliente...">
  <button type="submit" class="adm-search-btn">🔍</button>
</form>

{{-- Fila de cards FIFO --}}
@forelse($projects as $project)
  @php
    $hoursWaiting = $project->created_at->diffInHours(now());
    if ($hoursWaiting < 24)      { $urgency = 'green';  $urgencyLabel = 'DENTRO DO PRAZO'; }
    elseif ($hoursWaiting < 48)  { $urgency = 'yellow'; $urgencyLabel = 'ATENÇÃO'; }
    else                         { $urgency = 'red';    $urgencyLabel = 'URGENTE'; }
  @endphp

  <a href="{{ route('admin.projects.show', $project) }}" class="adm-project-card adm-project-card--{{ $urgency }}">
    <div class="adm-proj-urgency adm-urgency--{{ $urgency }}">{{ $urgencyLabel }}</div>

    <div class="adm-proj-main">
      <div class="adm-proj-info">
        <div class="adm-proj-name">{{ $project->name }}</div>
        <div class="adm-proj-client">👤 {{ $project->client->name }}</div>
        <div class="adm-proj-pack">📦 {{ $project->agreed_package_name }}</div>
      </div>
      <div class="adm-proj-meta">
        <div class="adm-proj-status adm-status--{{ str_replace('_', '-', $project->status) }}">
          {{ strtoupper(str_replace('_', ' ', $project->status)) }}
        </div>
        <div class="adm-proj-value">R$ {{ number_format($project->agreed_final_value, 2, ',', '.') }}</div>
        <div class="adm-proj-time">⏱ {{ $project->created_at->diffForHumans() }}</div>
      </div>
    </div>
  </a>

@empty
  <div class="adm-empty">Nenhum projeto encontrado com os filtros atuais.</div>
@endforelse

<div class="adm-pagination">
  {{ $projects->links() }}
</div>

@endsection

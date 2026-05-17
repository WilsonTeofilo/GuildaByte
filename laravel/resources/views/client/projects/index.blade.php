@extends('layouts.client')

@section('title', 'Meus Projetos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('style/projects.css') }}?v=1.0">
@endpush

@section('content')

<div class="rpg-header">
  <h1>LEADERBOARD</h1>
  <p>Missões em andamento e concluídas</p>
</div>

<div class="rpg-list">
  @forelse($projects as $project)
    @php
      // Simulação de progresso baseado no status ID
      // Num caso real, isso pode vir do BD ou logica da fase.
      $progress = min(100, max(5, ($project->status_id * 10) + 10));
      $isWaiting = $project->status_id === 1; // 1 = Aguardando
    @endphp
    
    <div class="rpg-card">
      <div class="rpg-card-top">
        <h2>{{ $project->name }}</h2>
        <span class="rpg-badge {{ $isWaiting ? 'waiting' : '' }}">
          {{ $project->status->name ?? 'Aguardando' }}
        </span>
      </div>

      <div class="rpg-progress-container">
        <div class="rpg-progress-bar" style="width: {{ $progress }}%;"></div>
        <span class="rpg-progress-text">LVL {{ $project->status_id }} - {{ $progress }}% CONCLUIDO</span>
      </div>

      <div class="rpg-card-footer">
        <div class="rpg-info-group">
          <span class="rpg-info-label">Pacote Base:</span>
          <span class="rpg-info-value">{{ $project->packageVersion->package_id ?? 'Custom' }}</span>
        </div>
        <div class="rpg-info-group">
          <span class="rpg-info-label">Criado em:</span>
          <span class="rpg-info-value">{{ $project->created_at->format('d/m/Y') }}</span>
        </div>
        <a href="{{ route('client.projects.show', $project->id) }}" class="rpg-btn">ABRIR MAPA</a>
      </div>
    </div>
  @empty
    <div class="empty-state">
      <p style="margin-bottom: 1rem; font-size: 2rem;">🛡️</p>
      <p>Nenhuma missão iniciada.</p>
      <a href="{{ route('client.wizard') }}" style="color: var(--gb-green); text-decoration: underline; margin-top: 1rem; display: inline-block;">Iniciar Nova Missão</a>
    </div>
  @endforelse
</div>
@endsection

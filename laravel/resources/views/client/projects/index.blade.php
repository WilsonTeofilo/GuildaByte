@extends('layouts.client')

@section('title', 'Meus Projetos')

@section('content')
<style>
  .rpg-header {
    text-align: center;
    margin-bottom: 2rem;
  }
  .rpg-header h1 {
    font-family: 'Press Start 2P', monospace;
    font-size: 1.5rem;
    color: var(--gb-green);
    text-shadow: 0 0 8px rgba(146, 255, 203, 0.4);
    margin-bottom: 0.5rem;
  }
  .rpg-header p {
    font-family: monospace;
    color: var(--gb-muted);
    font-size: 0.8rem;
  }

  .rpg-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    max-width: 800px;
    margin: 0 auto;
  }

  .rpg-card {
    background: #0a0a10;
    border: 2px solid var(--gb-border);
    border-radius: 8px;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
  }

  .rpg-card:hover {
    border-color: var(--gb-purple);
    box-shadow: 0 0 15px rgba(127, 119, 221, 0.2);
    transform: translateY(-2px);
  }

  .rpg-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .rpg-card-top h2 {
    font-size: 1.25rem;
    color: var(--gb-text);
    margin: 0;
  }

  .rpg-badge {
    background: rgba(146, 255, 203, 0.1);
    color: var(--gb-green);
    border: 1px solid var(--gb-green);
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-family: 'Press Start 2P', monospace;
    font-size: 0.6rem;
    text-transform: uppercase;
  }

  .rpg-badge.waiting {
    color: var(--gb-danger);
    border-color: var(--gb-danger);
    background: rgba(255, 120, 147, 0.1);
  }

  .rpg-progress-container {
    background: #101018;
    border: 2px solid #26215c;
    height: 24px;
    border-radius: 4px;
    position: relative;
    padding: 2px;
  }

  .rpg-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #534ab7 0%, #7f77dd 100%);
    border-radius: 2px;
    transition: width 1s ease-in-out;
  }

  .rpg-progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-family: 'Press Start 2P', monospace;
    font-size: 0.5rem;
    color: #fff;
    text-shadow: 1px 1px 0 #000;
  }

  .rpg-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid var(--gb-border);
    padding-top: 1rem;
    margin-top: 0.5rem;
  }

  .rpg-info-group {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
  }

  .rpg-info-label {
    font-family: monospace;
    font-size: 0.7rem;
    color: var(--gb-muted);
  }

  .rpg-info-value {
    color: var(--gb-text);
    font-size: 0.9rem;
    font-weight: 600;
  }

  .rpg-btn {
    background: transparent;
    border: 2px solid var(--gb-purple);
    color: var(--gb-purple-light);
    padding: 0.5rem 1rem;
    font-family: 'Press Start 2P', monospace;
    font-size: 0.6rem;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.2s;
  }

  .rpg-btn:hover {
    background: var(--gb-purple);
    color: #fff;
  }

  .empty-state {
    text-align: center;
    padding: 4rem 1rem;
    border: 2px dashed var(--gb-border);
    border-radius: 8px;
    font-family: monospace;
    color: var(--gb-muted);
  }
</style>

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

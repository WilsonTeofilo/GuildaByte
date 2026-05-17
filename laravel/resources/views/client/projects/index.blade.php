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
      $progress = $project->progress_percent;
      $isWaiting = in_array($project->status, ['received', 'in_analysis']);
    @endphp
    
    <div class="rpg-card">
      <div class="rpg-card-top">
        <h2>{{ $project->name }}</h2>
        <span class="rpg-badge {{ $isWaiting ? 'waiting' : '' }}">
          {{ ucfirst(str_replace('_', ' ', $project->status)) }}
        </span>
      </div>

      <div class="rpg-progress-container">
        <div class="rpg-progress-bar" style="width: {{ $progress }}%;"></div>
        <span class="rpg-progress-text">{{ $progress }}% CONCLUÍDO</span>
      </div>

      <div class="rpg-card-footer">
        <div class="rpg-info-group">
          <span class="rpg-info-label">Pacote Base:</span>
          <span class="rpg-info-value">{{ $project->agreed_package_name }}</span>
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
      <p class="proj-empty-emoji">🛡️</p>
      <p>Nenhuma missão iniciada.</p>
      <a href="{{ route('client.wizard') }}" class="proj-new-link">Iniciar Nova Missão</a>
    </div>
  @endforelse
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Admin — Guilda Calls')

@section('content')

<div class="adm-page-header">
  <h1 class="adm-title">CALLS.EXE — DASHBOARD</h1>
  <p class="adm-subtitle">Monitoramento de Reuniões e Pautas</p>
</div>

<div class="adm-pkg-grid" style="grid-template-columns: 1fr; margin-bottom: 2rem;">
  <div style="display:flex; gap: 1rem;">
    <a href="{{ route('admin.calls.slots') }}" class="adm-action-btn adm-btn--purple">GERENCIAR HORÁRIOS</a>
  </div>
</div>

<div class="adm-pkg-grid" style="grid-template-columns: 1fr 1fr;">

  {{-- CALL ATIVA --}}
  <div class="adm-section">
    <h2 class="adm-section-title">▶ CALL EM ANDAMENTO</h2>
    @if($active)
      <div style="background: rgba(146, 255, 203, 0.1); padding: 1rem; border: 1px solid #92ffcb; border-radius: 8px;">
        <h3 style="color: #92ffcb; font-size: 1.2rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $active->project->name }}</h3>
        <p style="color: #f6f4ff; margin-bottom: 1rem;">Cliente: {{ $active->project->client->name }}</p>
        <p style="font-size: 0.85rem; color: #aaa4bc; margin-bottom: 1.5rem;">Pauta: {{ Str::limit($active->agenda, 100) }}</p>
        
        <a href="{{ route('admin.calls.active', $active) }}" class="adm-action-btn adm-btn--green" style="display: inline-block; text-align: center;">RETORNAR PARA SALA</a>
      </div>
    @else
      <div class="adm-empty">Nenhuma call em andamento no momento.</div>
    @endif
  </div>

  {{-- PRÓXIMAS CALLS --}}
  <div class="adm-section">
    <h2 class="adm-section-title">▶ PRÓXIMAS CALLS (CONFIRMADAS E PENDENTES)</h2>
    @forelse($upcoming as $call)
      <div style="border-bottom: 1px solid #26215c; padding-bottom: 1rem; margin-bottom: 1rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
          <h4 style="color: #f6f4ff; font-weight: bold; font-size: 1rem;">{{ $call->project->name }}</h4>
          <span style="font-family: 'Press Start 2P', monospace; font-size: 0.5rem; padding: 4px 8px; border: 1px solid {{ $call->status == 'pending' ? '#ff7893' : '#92ffcb' }}; color: {{ $call->status == 'pending' ? '#ff7893' : '#92ffcb' }};">
            {{ strtoupper($call->status) }}
          </span>
        </div>
        <p style="font-size: 0.8rem; color: #aaa4bc; margin-bottom: 0.5rem;">
          Data: {{ $call->scheduled_at->format('d/m/Y H:i') }} | Cliente: {{ $call->project->client->name }}
        </p>
        <p style="font-size: 0.8rem; color: #afa9ec; margin-bottom: 1rem; border-left: 2px solid #26215c; padding-left: 0.5rem;">
          "{{ $call->agenda }}"
        </p>

        <div style="display: flex; gap: 0.5rem;">
          @if($call->status === 'pending')
            <form method="POST" action="{{ route('admin.calls.confirm', $call) }}">
              @csrf <button class="adm-action-btn adm-btn--green adm-btn--sm">CONFIRMAR</button>
            </form>
            <form method="POST" action="{{ route('admin.calls.reject', $call) }}">
              @csrf 
              <input type="hidden" name="rejection_reason" value="Horário indisponível por conflito interno">
              <button class="adm-action-btn adm-btn--sm" style="border:1px solid #ff7893; color: #ff7893; background:transparent;">REJEITAR</button>
            </form>
          @elseif($call->status === 'confirmed')
            @if(!$active)
              <form method="POST" action="{{ route('admin.calls.start', $call) }}">
                @csrf <button class="adm-action-btn adm-btn--green adm-btn--sm">INICIAR SALA</button>
              </form>
            @endif
          @endif
        </div>
      </div>
    @empty
      <div class="adm-empty">A agenda está limpa. Nenhuma call prevista.</div>
    @endforelse
  </div>

</div>

@endsection

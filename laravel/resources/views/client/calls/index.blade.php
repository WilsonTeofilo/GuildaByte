@extends('layouts.client')
@section('title', 'Histórico de Calls | ' . $project->name)

@section('content')

<div class="adm-page-header" style="display: flex; justify-content: space-between; align-items: center;">
  <div>
    <h1 class="adm-title">HISTORICO.EXE — CALLS</h1>
    <p class="adm-subtitle">Registro de todas as reuniões e atas do projeto</p>
  </div>
  @if(in_array($project->status, ['in_progress', 'testing']))
    <a href="{{ route('projects.calls.create', $project) }}" class="adm-action-btn adm-btn--green">
      + AGENDAR NOVA CALL
    </a>
  @endif
</div>

@if(session('success'))
  <div class="adm-alert adm-alert--success">✓ {{ session('success') }}</div>
@endif

<div class="adm-section">
  @forelse($calls as $call)
    <div style="display:flex; gap:1rem; padding-bottom:1.5rem; position:relative; border-bottom:1px solid #26215c; margin-bottom:1.5rem">
      <div style="width: 2.5rem; height: 2.5rem; background: #13131c; border: 2px solid {{ $call->status === 'cancelled' ? '#ff7893' : ($call->status === 'completed' ? '#92ffcb' : '#7f77dd') }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
        @if($call->status === 'cancelled') ✕
        @elseif($call->status === 'completed') ✓
        @else ⏳
        @endif
      </div>
      <div style="flex:1">
        <div style="display:flex; justify-content:space-between; margin-bottom:0.25rem;">
          <h3 style="font-size:1rem; font-weight:bold; color: #f6f4ff;">
            {{ ucfirst(str_replace('_', ' ', $call->type)) }}
          </h3>
          <span style="font-family:'Press Start 2P', monospace; font-size:0.5rem; padding:4px 8px; border:1px solid #7f77dd; color:#7f77dd;">
            {{ strtoupper($call->status) }}
          </span>
        </div>
        <div style="font-size:0.8rem; color:#aaa4bc; margin-bottom:0.5rem">
          {{ $call->scheduled_at->format('d/m/Y \à\s H:i') }} 
          @if($call->duration_minutes) — {{ $call->duration_minutes }}min @endif
        </div>
        <div style="font-size:0.9rem; color:#aaa4bc; margin-bottom:0.75rem">
          <strong>Pauta:</strong> {{ $call->agenda }}
        </div>
        
        @if($call->status === 'cancelled')
          <div style="font-size:0.85rem; color:#ff7893; background:rgba(255,120,147,0.1); padding:0.75rem; border-left:2px solid #ff7893;">
            Motivo do cancelamento: {{ $call->cancellation_reason ?: 'Não informado' }}
          </div>
        @endif

        @if($call->checklist)
          <div style="background:#0a0a10; border:1px solid #26215c; padding:1rem;">
            <div style="font-family:'Press Start 2P', monospace; font-size:0.5rem; color:#afa9ec; margin-bottom:0.75rem;">ATA — ITENS ACORDADOS</div>
            @foreach($call->checklist as $item)
              <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem; align-items:flex-start; font-size:0.875rem;">
                <span style="display:inline-block; width:14px; height:14px; border:1px solid {{ isset($item['done']) && $item['done'] ? '#92ffcb' : '#26215c' }}; background:{{ isset($item['done']) && $item['done'] ? '#92ffcb' : 'transparent' }}; margin-top:2px;"></span>
                <span style="color:#f6f4ff">{{ $item['text'] }}</span>
              </div>
            @endforeach
          </div>
        @endif

        @if(in_array($call->status, ['pending', 'confirmed']))
          <div style="margin-top:1rem;">
             {{-- Ações futuras para o cliente cancelar etc --}}
             @if($call->status === 'confirmed' && $call->meet_link)
               <a href="{{ $call->meet_link }}" target="_blank" class="adm-action-btn adm-btn--green adm-btn--sm">ENTRAR NA SALA</a>
             @endif
          </div>
        @endif
      </div>
    </div>
  @empty
    <div class="adm-empty">Nenhuma call agendada para este projeto.</div>
  @endforelse
</div>

@endsection

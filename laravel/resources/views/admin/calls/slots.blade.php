@extends('layouts.admin')

@section('title', 'Admin — Slots de Agenda')

@section('content')

<div class="adm-page-header">
  <div style="display:flex; justify-content:space-between; align-items:center;">
    <div>
      <h1 class="adm-title">SLOTS.EXE — AGENDA</h1>
      <p class="adm-subtitle">Disponibilize horários para os clientes agendarem calls</p>
    </div>
    <a href="{{ route('admin.calls.index') }}" class="adm-action-btn adm-btn--purple">← VOLTAR AO DASHBOARD</a>
  </div>
</div>

@if($errors->any())
  <div class="adm-alert adm-alert--error">
    @foreach($errors->all() as $e) <div>✗ {{ $e }}</div> @endforeach
  </div>
@endif

@if(session('success'))
  <div class="adm-alert adm-alert--success">✓ {{ session('success') }}</div>
@endif

<div class="adm-pkg-grid" style="grid-template-columns: 1fr 2fr;">

  {{-- CRIAR NOVO SLOT --}}
  <div class="adm-section">
    <h2 class="adm-section-title">▶ ABRIR NOVO HORÁRIO</h2>
    <form method="POST" action="{{ route('admin.calls.slots.store') }}">
      @csrf
      
      <div style="margin-bottom:1rem;">
        <label style="display:block; font-size:0.75rem; color:#aaa4bc; margin-bottom:0.5rem;">Data e Hora (min. 24h futuro)</label>
        <input type="datetime-local" name="start_time" class="adm-input" required>
      </div>

      <div style="margin-bottom:1.5rem;">
        <label style="display:block; font-size:0.75rem; color:#aaa4bc; margin-bottom:0.5rem;">Vincular a Projeto (Opcional)</label>
        <select name="project_id" class="adm-input">
          <option value="">-- Slot Global (Qualquer cliente) --</option>
          @foreach($projects as $p)
            <option value="{{ $p->id }}">{{ $p->name }} - {{ $p->client->name }}</option>
          @endforeach
        </select>
        <p style="font-size:0.65rem; color:#afa9ec; margin-top:5px;">Se selecionar um projeto, apenas este cliente verá o horário.</p>
      </div>

      <button type="submit" class="adm-action-btn adm-btn--green adm-btn--full" style="padding:0.75rem;">
        + ABRIR SLOT
      </button>
    </form>
  </div>

  {{-- LISTA DE SLOTS ABERTOS --}}
  <div class="adm-section">
    <h2 class="adm-section-title">▶ SLOTS DISPONÍVEIS</h2>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
      @forelse($slots as $slot)
        <div style="border: 1px solid {{ $slot->is_booked ? '#ff7893' : '#92ffcb' }}; padding:1rem; position:relative; background: #0a0a10;">
          <div style="font-size: 0.75rem; color:#aaa4bc; margin-bottom:0.25rem;">
            {{ $slot->start_time->format('d/m/Y') }}
          </div>
          <div style="font-size: 1.2rem; font-weight:bold; color:#f6f4ff; margin-bottom:0.5rem;">
            {{ $slot->start_time->format('H:i') }}
          </div>
          
          <div style="font-family:'Press Start 2P', monospace; font-size:0.5rem; color: {{ $slot->is_booked ? '#ff7893' : '#92ffcb' }}; margin-bottom:0.5rem;">
            {{ $slot->is_booked ? 'RESERVADO' : 'LIVRE' }}
          </div>

          @if($slot->project_id)
            <div style="font-size:0.75rem; color:#afa9ec; border-top:1px solid #26215c; padding-top:0.5rem; margin-top:0.5rem;">
              🔒 Exclusivo: {{ Str::limit($slot->project->name, 20) }}
            </div>
          @endif

          @if(!$slot->is_booked)
            <form method="POST" action="{{ route('admin.calls.slots.destroy', $slot) }}" style="position:absolute; top:10px; right:10px;">
              @csrf @method('DELETE')
              <button class="adm-action-btn adm-btn--sm" style="border:1px solid #ff7893; color:#ff7893; background:transparent;" onsubmit="return confirm('Apagar este slot?')">X</button>
            </form>
          @endif
        </div>
      @empty
        <div class="adm-empty" style="grid-column: span 2">Nenhum slot configurado para o futuro.</div>
      @endforelse
    </div>
  </div>

</div>

@endsection

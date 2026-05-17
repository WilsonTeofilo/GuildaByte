@extends('layouts.client')
@section('title', 'Agendar Call | ' . $project->name)

@section('content')

<div class="adm-page-header">
  <h1 class="adm-title">AGENDAR.EXE — NOVA CALL</h1>
  <p class="adm-subtitle">Alinhe status, tire dúvidas ou faça revisão de entregas</p>
</div>

@if($errors->any())
  <div class="adm-alert adm-alert--error">
    @foreach($errors->all() as $e) <div>✗ {{ $e }}</div> @endforeach
  </div>
@endif

<form method="POST" action="{{ route('projects.calls.store', $project) }}">
  @csrf

  <div class="adm-pkg-grid">

    {{-- COLUNA 1: PROJETO E TIPO --}}
    <div>
      <h2 class="adm-section-title">▶ PROJETO</h2>
      <div class="adm-section">
        <div style="font-size:1rem; font-weight:bold; color: #f6f4ff; margin-bottom:0.5rem">{{ $project->name }}</div>
        <div style="font-size:0.875rem; color: #aaa4bc;">Plano {{ $project->agreed_package_name }} — 
          @if(in_array(strtolower($project->agreed_package_name), ['start']))
             <span class="adm-value--red">Call avulsa R$ 70</span>
          @else
             <span class="adm-value--green">Call gratuita inclusa</span>
          @endif
        </div>
      </div>

      <h2 class="adm-section-title">▶ TIPO DE CALL</h2>
      <div class="adm-section">
        <div style="display:flex; flex-direction:column; gap:0.5rem;">
          <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
            <input type="radio" name="type" value="status_alignment" required checked>
            Alinhamento de status
          </label>
          <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
            <input type="radio" name="type" value="delivery_review" required>
            Revisão de entrega
          </label>
          <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
            <input type="radio" name="type" value="urgency" required>
            Urgência / Incidente
          </label>
        </div>
      </div>
    </div>

    {{-- COLUNA 2: SLOTS E PAUTA --}}
    <div>
      <h2 class="adm-section-title">▶ HORÁRIOS DISPONÍVEIS</h2>
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; margin-bottom:1.5rem">
        @forelse($slots as $slot)
        <label class="adm-section" style="cursor:pointer; text-align:center; padding:1rem; display:block; border-color: #26215c;">
          <input type="radio" name="call_slot_id" value="{{ $slot->id }}" required style="display:none;" onchange="document.querySelectorAll('.slot-lbl').forEach(l=>l.style.borderColor='#26215c'); this.parentElement.style.borderColor='#92ffcb';" class="slot-lbl">
          <div style="font-size:0.75rem; color: #aaa4bc;">{{ $slot->start_time->format('D d/m') }}</div>
          <div style="font-size:1.25rem; font-weight:bold; color: #f6f4ff;">{{ $slot->start_time->format('H:i') }}</div>
        </label>
        @empty
          <div class="adm-empty" style="grid-column: span 2">Nenhum horário disponível para agendamento no momento.</div>
        @endforelse
      </div>

      <h2 class="adm-section-title">▶ PAUTA (OBRIGATÓRIA)</h2>
      <div class="adm-section">
        <textarea name="agenda" class="adm-textarea" rows="3" placeholder="O que você quer discutir nessa call?&#10;Seja objetivo — isso vai para a ata depois" required></textarea>
      </div>

      <button type="submit" class="adm-action-btn adm-btn--green adm-btn--full" style="padding:1rem">
        ✓ CONFIRMAR AGENDAMENTO
      </button>
      <div style="font-size:0.75rem; color: #aaa4bc; text-align:center; margin-top:0.75rem">
        Mínimo 24h de antecedência • Cancelamento com 4h antes
      </div>
    </div>

  </div>
</form>

@endsection

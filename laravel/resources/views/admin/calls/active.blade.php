@extends('layouts.admin')

@section('title', 'SALA ATIVA — ' . $call->project->name)

@section('content')

<div class="adm-page-header" style="border-bottom: 2px solid #92ffcb; padding-bottom: 1rem; margin-bottom: 2rem;">
  <div style="display:flex; justify-content:space-between; align-items:center;">
    <div>
      <h1 class="adm-title" style="color:#92ffcb;">SALA DE CALL ATIVA</h1>
      <p class="adm-subtitle">Projeto: {{ $call->project->name }} | Cliente: {{ $call->project->client->name }}</p>
    </div>
    <div style="text-align:right;">
      <div style="font-family:'Press Start 2P', monospace; font-size: 1.5rem; color:#ff7893;" id="timer">00:00</div>
      <div style="font-size: 0.6rem; color:#aaa4bc; margin-top: 5px;">TEMPO DECORRIDO</div>
    </div>
  </div>
</div>

<div class="adm-pkg-grid" style="grid-template-columns: 1fr 1fr;">

  {{-- INFO E ATA --}}
  <div>
    <h2 class="adm-section-title">▶ INFORMAÇÕES E PAUTA</h2>
    <div class="adm-section" style="margin-bottom: 1.5rem;">
      <p style="color:#afa9ec; font-size: 0.85rem; margin-bottom:0.5rem;"><strong>Tipo:</strong> {{ ucfirst(str_replace('_', ' ', $call->type)) }}</p>
      <p style="color:#f6f4ff; font-size: 0.9rem; line-height: 1.5; padding: 1rem; background: #0a0a10; border-left: 2px solid #7f77dd;">
        {{ $call->agenda }}
      </p>
    </div>

    <h2 class="adm-section-title">▶ FINALIZAR CALL E SALVAR ATA</h2>
    <div class="adm-section">
      <form method="POST" action="{{ route('admin.calls.complete', $call) }}" id="complete-form">
        @csrf
        <input type="hidden" name="duration_minutes" id="duration_minutes" value="0">
        
        <label style="display:block; font-size:0.75rem; color:#aaa4bc; margin-bottom:0.5rem;">CHECKLIST ACORDADO (Ficará visível para o cliente)</label>
        <div id="checklist-container" style="margin-bottom:1rem; display:flex; flex-direction:column; gap:0.5rem;">
          {{-- JS preenche --}}
          <div style="display:flex; gap:0.5rem;">
            <input type="text" name="checklist[0][text]" class="adm-input" placeholder="Novo acordo ou entrega...">
            <input type="hidden" name="checklist[0][done]" value="0">
          </div>
        </div>
        <button type="button" onclick="addChecklist()" class="adm-action-btn adm-btn--sm" style="margin-bottom: 1.5rem; border:1px solid #7f77dd; color:#7f77dd; background:transparent;">+ ADICIONAR ITEM</button>

        <label style="display:block; font-size:0.75rem; color:#aaa4bc; margin-bottom:0.5rem;">NOTAS INTERNAS (Só a Guilda vê)</label>
        <textarea name="admin_notes" class="adm-textarea" rows="4" placeholder="Observações sigilosas..." style="margin-bottom: 1.5rem;"></textarea>

        <button type="submit" class="adm-action-btn adm-btn--red adm-btn--full" style="padding:1rem;" onsubmit="return confirm('Deseja encerrar a call e enviar a ata ao cliente?')">
          🛑 ENCERRAR CALL
        </button>
      </form>
    </div>
  </div>

  {{-- CHAT RÁPIDO DO PROJETO --}}
  <div>
    <h2 class="adm-section-title">▶ CHAT DO PROJETO</h2>
    <div class="adm-section" style="height: 60vh;">
      <iframe src="{{ route('admin.chat.index', $call->project) }}" frameborder="0" style="width:100%; height:100%; border-radius: 8px;"></iframe>
    </div>
  </div>

</div>

<script>
  let minutes = 0;
  let seconds = 0;
  let timerEl = document.getElementById('timer');
  let durationInput = document.getElementById('duration_minutes');
  
  setInterval(() => {
    seconds++;
    if(seconds === 60) {
      minutes++;
      seconds = 0;
      durationInput.value = minutes;
    }
    let m = minutes < 10 ? "0" + minutes : minutes;
    let s = seconds < 10 ? "0" + seconds : seconds;
    timerEl.innerText = m + ":" + s;
  }, 1000);

  let checkIndex = 1;
  function addChecklist() {
    let container = document.getElementById('checklist-container');
    let div = document.createElement('div');
    div.style.cssText = "display:flex; gap:0.5rem;";
    div.innerHTML = `
      <input type="text" name="checklist[${checkIndex}][text]" class="adm-input" placeholder="Novo acordo ou entrega...">
      <input type="hidden" name="checklist[${checkIndex}][done]" value="0">
    `;
    container.appendChild(div);
    checkIndex++;
  }
</script>

@endsection

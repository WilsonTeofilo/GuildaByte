@extends('layouts.client')

@section('title', 'Detalhe do Projeto')

@section('content')
<style>
  .rpg-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    border-bottom: 1px dashed var(--gb-border);
    padding-bottom: 1rem;
  }
  .rpg-page-header h1 {
    font-size: 1.5rem;
    color: var(--gb-text);
    margin: 0;
  }
  .rpg-status-badge {
    background: rgba(127, 119, 221, 0.1);
    color: var(--gb-purple-light);
    border: 1px solid var(--gb-purple);
    padding: 0.3rem 0.6rem;
    border-radius: 4px;
    font-family: 'Press Start 2P', monospace;
    font-size: 0.6rem;
    text-transform: uppercase;
  }
  
  .rpg-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    border-bottom: 2px solid #26215c;
  }
  
  .rpg-tab {
    padding: 0.8rem 1.5rem;
    background: #0a0a10;
    border: 2px solid #26215c;
    border-bottom: none;
    border-radius: 8px 8px 0 0;
    color: var(--gb-muted);
    font-family: 'Press Start 2P', monospace;
    font-size: 0.6rem;
    cursor: pointer;
    transition: all 0.2s;
  }
  
  .rpg-tab:hover {
    color: var(--gb-text);
    background: #101018;
  }
  
  .rpg-tab.active {
    background: #101018;
    color: var(--gb-green);
    border-color: var(--gb-green);
    border-bottom: 2px solid #101018;
    margin-bottom: -2px;
  }

  .rpg-tab-content {
    display: none;
    background: #101018;
    border: 2px solid var(--gb-green);
    border-top: none;
    padding: 2rem;
    border-radius: 0 8px 8px 8px;
    min-height: 300px;
  }
  
  .rpg-tab-content.active {
    display: block;
  }

  /* Timeline Styles */
  .timeline {
    position: relative;
    padding-left: 2rem;
    margin: 0;
    list-style: none;
  }
  
  .timeline::before {
    content: '';
    position: absolute;
    left: 7px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--gb-border);
  }
  
  .timeline-item {
    position: relative;
    margin-bottom: 2rem;
  }
  
  .timeline-item::before {
    content: '';
    position: absolute;
    left: -2rem;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--gb-bg);
    border: 2px solid var(--gb-purple);
  }
  
  .timeline-item.completed::before {
    background: var(--gb-green);
    border-color: var(--gb-green);
    box-shadow: 0 0 10px var(--gb-green);
  }

  .timeline-date {
    font-family: monospace;
    font-size: 0.75rem;
    color: var(--gb-muted);
    margin-bottom: 0.2rem;
    display: block;
  }
  
  .timeline-title {
    color: var(--gb-text);
    font-weight: 600;
    margin-bottom: 0.5rem;
  }

  .placeholder-text {
    color: var(--gb-muted);
    font-family: monospace;
    text-align: center;
    margin-top: 2rem;
  }
</style>

<div class="rpg-page-header">
  <h1>{{ $project->name }}</h1>
  <span class="rpg-status-badge">{{ $project->status->name ?? 'Aguardando' }}</span>
</div>

<div class="rpg-tabs">
  <button class="rpg-tab active" onclick="openTab(event, 'tab-timeline')">TIMELINE</button>
  <button class="rpg-tab" onclick="openTab(event, 'tab-proposta')">PROPOSTA</button>
  <button class="rpg-tab" onclick="openTab(event, 'tab-arquivos')">ARQUIVOS</button>
  <button class="rpg-tab" onclick="openTab(event, 'tab-mensagens')">MENSAGENS</button>
</div>

<div id="tab-timeline" class="rpg-tab-content active">
  <ul class="timeline">
    <li class="timeline-item completed">
      <span class="timeline-date">{{ $project->created_at->format('d/m/Y H:i') }}</span>
      <div class="timeline-title">Pedido Criado</div>
      <p class="text-sm" style="color: var(--gb-muted);">Você iniciou a missão selecionando o pacote base.</p>
    </li>
    <li class="timeline-item">
      <span class="timeline-date">Pendente</span>
      <div class="timeline-title">Briefing e Proposta</div>
      <p class="text-sm" style="color: var(--gb-muted);">Aguardando envio e aceite da proposta comercial formal.</p>
    </li>
    <li class="timeline-item">
      <span class="timeline-date">Em breve</span>
      <div class="timeline-title">Início do Desenvolvimento</div>
      <p class="text-sm" style="color: var(--gb-muted);">A Guilda começa a codificar a sua solução.</p>
    </li>
  </ul>
</div>

<div id="tab-proposta" class="rpg-tab-content">
  <h2 style="color: var(--gb-purple-light); margin-bottom: 1rem;">Documento de Proposta</h2>

  @if($project->contractAcceptances->isNotEmpty())
    {{-- Proposta já aceita --}}
    <div style="background: rgba(146,255,203,0.08); border: 1px solid var(--gb-green); border-radius: 8px; padding: 1.5rem; margin-bottom: 1rem;">
      <p style="color: var(--gb-green); font-family: 'Press Start 2P', monospace; font-size: 0.6rem; margin-bottom: 0.8rem;">✓ PROPOSTA ACEITA DIGITALMENTE</p>
      @php $acceptance = $project->contractAcceptances->first(); @endphp
      <div style="font-family: monospace; font-size: 0.8rem; color: var(--gb-muted); line-height: 2;">
        <div>Data: {{ $acceptance->accepted_at->format('d/m/Y \à\s H:i') }}</div>
        <div>IP: {{ $acceptance->ip_address }}</div>
        <div style="word-break: break-all;">Dispositivo: {{ Str::limit($acceptance->user_agent, 80) }}</div>
      </div>
    </div>
  @else
    {{-- Proposta pendente de aceite --}}
    <div style="background: #0a0a10; padding: 1.5rem; border: 1px solid var(--gb-border); border-radius: 8px; margin-bottom: 1.5rem;">
      <p style="color: var(--gb-muted); font-family: monospace; font-size: 0.85rem;">A proposta formal ainda está sendo preparada pela equipe.</p>
    </div>

    {{-- Botão de aceite — só mostra se projeto estiver em estado de aguardando aceite --}}
    @if($project->status === 'awaiting_acceptance')
      <div style="border: 2px solid var(--gb-green); border-radius: 8px; padding: 1.5rem; background: rgba(146,255,203,0.04);">
        <p style="font-size: 0.8rem; color: var(--gb-text); margin-bottom: 1rem;">
          Ao aceitar, você confirma que leu e concorda com os termos desta proposta.<br>
          <small style="color: var(--gb-muted);">Seu IP e informações de dispositivo serão registrados como prova de aceite digital.</small>
        </p>
        <form method="POST" action="{{ route('client.projects.accept', $project) }}" onsubmit="return confirm('Confirmar aceite digital? Esta ação é irreversível.')">
          @csrf
          <button type="submit" style="background: var(--gb-green); color: #0a0a10; border: none; padding: 0.8rem 2rem; font-family: 'Press Start 2P', monospace; font-size: 0.65rem; border-radius: 4px; cursor: pointer; transition: opacity 0.2s;">
            ACEITAR PROPOSTA DIGITALMENTE
          </button>
        </form>
      </div>
    @endif
  @endif
</div>

<div id="tab-arquivos" class="rpg-tab-content">
  <div class="placeholder-text">Inventário vazio. Nenhum arquivo enviado ainda.</div>
</div>

<div id="tab-mensagens" class="rpg-tab-content">
  <div class="placeholder-text">O chat da guilda está silencioso...</div>
</div>

<script>
function openTab(evt, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("rpg-tab-content");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
    tabcontent[i].classList.remove("active");
  }
  tablinks = document.getElementsByClassName("rpg-tab");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].classList.remove("active");
  }
  document.getElementById(tabName).style.display = "block";
  document.getElementById(tabName).classList.add("active");
  evt.currentTarget.classList.add("active");
}
</script>
@endsection

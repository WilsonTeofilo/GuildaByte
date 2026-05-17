@extends('layouts.admin')

@section('title', 'Guilda Board — ' . $project->name)

@section('content')

<div class="adm-page-header" style="display:flex; justify-content:space-between; align-items:center;">
  <div>
    <h1 class="adm-title" style="color: #afa9ec;">GUILDA BOARD</h1>
    <p class="adm-subtitle">Projeto: <span style="color:#f6f4ff;">{{ $project->name }}</span> | Cliente: {{ $project->client->name }}</p>
  </div>
  <div style="display:flex; gap:10px;">
    <a href="{{ route('admin.chat.index', $project) }}" class="adm-action-btn adm-btn--purple adm-btn--sm">CHAT COM CLIENTE</a>
  </div>
</div>

<div class="board-container">
  
  @foreach($board->columns as $col)
    {{-- COLUNA --}}
    <div class="board-col-wrapper">
      
      {{-- HEADER COLUNA --}}
      <div class="board-col-header">
        <h3 style="font-size: 0.9rem; font-weight:bold; color:#f6f4ff;">{{ strtoupper($col->title) }}</h3>
        <span style="background:#26215c; color:#afa9ec; padding:2px 6px; font-size:0.7rem; border-radius:4px;">{{ $col->cards->count() }}</span>
      </div>

      {{-- CARDS CONTAINER --}}
      <div style="padding: 0.75rem; flex:1; overflow-y:auto; display:flex; flex-direction:column; gap:0.5rem;" class="board-column" data-col-id="{{ $col->id }}">
        
        @foreach($col->cards as $card)
          {{-- CARD --}}
          <div class="board-card-wrapper board-card" data-card-id="{{ $card->id }}" onclick="openCardModal({{ $card->id }})">
            
            <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
              @if($card->type === 'bug')
                <span class="board-badge-bug">BUG</span>
              @elseif($card->type === 'feature')
                <span class="board-badge-feature">FEATURE</span>
              @else
                <span class="board-badge-task">TASK</span>
              @endif
              <span style="font-size: 0.65rem; color:#aaa4bc;">#{{ $card->id }}</span>
            </div>

            <div style="font-size: 0.85rem; color:#f6f4ff; margin-bottom:0.5rem;">{{ $card->title }}</div>
            
            <div style="display:flex; gap:0.5rem; font-size: 0.7rem; color:#aaa4bc;">
              <span>💬 {{ $card->comments->count() }}</span>
            </div>
          </div>
        @endforeach

      </div>

      {{-- NOVO CARD BTN --}}
      <div style="padding: 0.75rem; border-top: 1px solid #26215c;">
        <button onclick="document.getElementById('new-card-form-{{ $col->id }}').style.display='block'; this.style.display='none'" style="width:100%; text-align:left; color:#afa9ec; background:transparent; border:none; cursor:pointer; font-size:0.8rem;">
          + Adicionar Card
        </button>
        <form id="new-card-form-{{ $col->id }}" style="display:none;" method="POST" action="{{ route('admin.board.cards.store', $col) }}">
          @csrf
          <input type="text" name="title" class="adm-input" placeholder="Título do card..." style="margin-bottom:0.5rem;" required autofocus>
          <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem;">
            <select name="type" class="adm-input" style="padding:0.25rem;">
              <option value="task">Task</option>
              <option value="feature">Feature</option>
              <option value="bug">Bug</option>
            </select>
          </div>
          <div style="display:flex; gap:0.5rem;">
            <button type="submit" class="adm-action-btn adm-btn--green adm-btn--sm">Salvar</button>
            <button type="button" class="adm-action-btn adm-btn--sm" onclick="this.closest('form').style.display='none'; this.closest('form').previousElementSibling.style.display='block'">X</button>
          </div>
        </form>
      </div>

    </div>
  @endforeach

  {{-- NOVA COLUNA --}}
  <div class="board-new-col">
    <form method="POST" action="{{ route('admin.board.columns.store', $board) }}">
      @csrf
      <input type="text" name="title" class="adm-input" placeholder="Nova Coluna..." style="margin-bottom:0.5rem;" required>
      <button type="submit" class="adm-action-btn adm-btn--purple adm-btn--sm">+ Adicionar</button>
    </form>
  </div>

</div>

{{-- Script simulação drag and drop - exigirá SortableJS em prod real, mas faremos o mock visual --}}
<script>
  // Apenas mockup visual/lógica base. SortableJS seria ideal aqui.
  function openCardModal(id) {
    alert('Na V6 completa, abrirá o modal detalhado do card #' + id + ' com comentários e activity logs.');
  }
</script>

@endsection

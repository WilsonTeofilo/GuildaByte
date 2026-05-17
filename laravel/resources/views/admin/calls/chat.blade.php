@extends('layouts.admin')

@section('title', 'Admin — Chat do Projeto')

@section('content')

{{-- Usaremos um grid onde a esquerda fica a lista de projetos, e a direita o chat --}}
<div style="display:flex; height: calc(100vh - 100px); gap:1rem;">
  
  {{-- BARRA LATERAL - PROJETOS --}}
  <div style="width: 300px; background: #0a0a10; border: 1px solid #26215c; display:flex; flex-direction:column;">
    <div style="padding: 1rem; border-bottom: 1px solid #26215c;">
      <h2 style="font-family:'Press Start 2P', monospace; font-size: 0.6rem; color:#afa9ec;">PROJETOS ATIVOS</h2>
    </div>
    <div style="flex:1; overflow-y:auto; padding: 0.5rem;">
      @foreach($projects as $p)
        <a href="{{ route('admin.chat.index', $p) }}" style="display:block; padding: 0.75rem; border-radius:4px; text-decoration:none; margin-bottom:0.25rem; {{ $p->id === $project->id ? 'background: rgba(127,119,221,0.15); border-left: 2px solid #7f77dd;' : 'color: #aaa4bc;' }}">
          <div style="font-size: 0.85rem; font-weight:bold; color: {{ $p->id === $project->id ? '#f6f4ff' : '#aaa4bc' }}">{{ $p->name }}</div>
          <div style="font-size: 0.7rem; color: #afa9ec;">{{ $p->client->name }}</div>
        </a>
      @endforeach
    </div>
  </div>

  {{-- JANELA DE CHAT --}}
  <div style="flex:1; background: #13131c; border: 1px solid #26215c; display:flex; flex-direction:column;">
    <div style="padding: 1rem; border-bottom: 1px solid #26215c; display:flex; justify-content:space-between; align-items:center;">
      <div>
        <h2 style="font-size: 1.1rem; color:#f6f4ff; margin-bottom:0.25rem;">CHAT — {{ strtoupper($project->name) }}</h2>
        <p style="font-size: 0.75rem; color:#aaa4bc;">Cliente: {{ $project->client->name }}</p>
      </div>
      <a href="{{ route('admin.board.show', $project) }}" class="adm-action-btn adm-btn--purple adm-btn--sm">IR PARA O BOARD</a>
    </div>

    <div style="flex:1; overflow-y:auto; padding: 1.5rem; display:flex; flex-direction:column; gap:1rem;" id="chat-history">
      @forelse($messages as $msg)
        @php
          // O Admin pode ser qualquer um com auth()->id() != client_id
          $isClient = $msg->user_id === $project->client_id;
        @endphp
        <div style="max-width: 70%; padding:0.75rem 1rem; font-size:0.875rem; line-height:1.45; {{ !$isClient ? 'align-self: flex-end; background: rgba(127,119,221,0.15); border: 1px solid rgba(127,119,221,0.2);' : 'align-self: flex-start; background: #1b1a26; border: 1px solid #26215c;' }}">
          <div style="color: #f6f4ff;">{{ $msg->content }}</div>
          <div style="font-size:0.65rem; color: #aaa4bc; margin-top:0.25rem; {{ !$isClient ? 'text-align:right;' : '' }}">
            {{ !$isClient ? $msg->user->name : 'Cliente' }} — {{ $msg->created_at->format('H:i') }}
          </div>
        </div>
      @empty
        <div class="adm-empty" style="margin:auto;">Sem mensagens.</div>
      @endforelse
    </div>

    <div style="padding: 1rem; border-top: 1px solid #26215c; background: #0a0a10;">
      <form method="POST" action="{{ route('admin.chat.send', $project) }}" style="display:flex; gap:0.5rem;">
        @csrf
        <input type="text" name="content" class="adm-input" placeholder="Mensagem para o cliente..." required autocomplete="off" autofocus style="flex:1;">
        <button type="submit" class="adm-action-btn adm-btn--purple" style="padding:0.75rem 1.5rem;">ENVIAR</button>
      </form>
    </div>
  </div>

</div>

<script>
  const chatHistory = document.getElementById('chat-history');
  chatHistory.scrollTop = chatHistory.scrollHeight;
</script>

@endsection

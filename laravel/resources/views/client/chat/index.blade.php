@extends('layouts.client')
@section('title', 'Chat | ' . $project->name)

@section('content')

<div class="adm-page-header">
  <h1 class="adm-title">CHAT.EXE — {{ strtoupper($project->name) }}</h1>
  <p class="adm-subtitle">Comunicação direta com a equipe GuildaByte</p>
</div>

<div class="adm-pkg-grid" style="grid-template-columns: 1fr; max-width:800px; margin:0 auto;">
  
  <div class="adm-section" style="display:flex; flex-direction:column; height: 60vh;">
    
    {{-- Histórico de mensagens --}}
    <div style="flex:1; overflow-y:auto; padding-right:1rem; display:flex; flex-direction:column; gap:0.75rem;" id="chat-history">
      @forelse($messages as $msg)
        @php
          $isMe = $msg->user_id === auth()->id();
        @endphp
        <div style="max-width: 80%; padding:0.75rem 1rem; font-size:0.875rem; line-height:1.45; {{ $isMe ? 'align-self: flex-end; background: rgba(127,119,221,0.15); border: 1px solid rgba(127,119,221,0.2);' : 'align-self: flex-start; background: #1b1a26; border: 1px solid #26215c;' }}">
          <div style="color: #f6f4ff;">{{ $msg->content }}</div>
          <div style="font-size:0.65rem; color: #aaa4bc; margin-top:0.25rem; {{ $isMe ? 'text-align:right;' : '' }}">
            {{ $isMe ? 'Você' : 'Guilda' }} — {{ $msg->created_at->format('H:i') }}
          </div>
        </div>
      @empty
        <div class="adm-empty" style="margin:auto;">Nenhuma mensagem neste projeto ainda. Envie um oi!</div>
      @endforelse
    </div>

    {{-- Input --}}
    <form method="POST" action="{{ route('projects.chat.store', $project) }}" style="margin-top:1.5rem; display:flex; gap:0.5rem; align-items:flex-start;">
      @csrf
      <div style="flex:1;">
        <input type="text" name="content" class="adm-input" placeholder="Digite sua mensagem..." required autocomplete="off" autofocus>
      </div>
      <button type="submit" class="adm-action-btn adm-btn--purple" style="padding:0.75rem 1.25rem">
        ▶
      </button>
    </form>
    
  </div>

</div>

<script>
  // Rolar para o final do chat ao carregar
  const chatHistory = document.getElementById('chat-history');
  chatHistory.scrollTop = chatHistory.scrollHeight;
</script>

@endsection

@extends('layouts.admin')
@section('title', 'Briefing — ' . $project->name)

@section('content')

<div class="adm-page-header">
  <div>
    <a href="{{ route('admin.projects.index') }}" class="adm-back-link">← FILA</a>
    <h1 class="adm-title">{{ $project->name }}</h1>
    <span class="adm-status-badge adm-status--{{ str_replace('_', '-', $project->status) }}">
      {{ strtoupper(str_replace('_', ' ', $project->status)) }}
    </span>
  </div>
</div>

@if(session('success'))
  <div class="adm-alert adm-alert--success">✓ {{ session('success') }}</div>
@endif
@if($errors->any())
  <div class="adm-alert adm-alert--error">
    @foreach($errors->all() as $e) <div>✗ {{ $e }}</div> @endforeach
  </div>
@endif

{{-- Layout duas colunas: 60% briefing | 40% ações --}}
<div class="adm-briefing-grid">

  {{-- ── COLUNA ESQUERDA: Briefing do cliente ── --}}
  <div class="adm-briefing-content">

    {{-- Dados do cliente --}}
    <div class="adm-section">
      <h2 class="adm-section-title">▶ CLIENTE</h2>
      <div class="adm-client-info">
        <div><span class="adm-label">Nome:</span> {{ $project->client->name }}</div>
        <div><span class="adm-label">E-mail:</span> {{ $project->client->email }}</div>
        <div><span class="adm-label">Telefone:</span> {{ $project->client->clientProfile?->phone ?? '—' }}</div>
        <div><span class="adm-label">Negócio:</span> {{ $project->client->clientProfile?->business_name ?? '—' }}</div>
        <div><span class="adm-label">Instagram:</span> {{ $project->client->clientProfile?->instagram ?? '—' }}</div>
        <div><span class="adm-label">Site:</span>
          @if($project->client->clientProfile?->website)
            <a href="{{ $project->client->clientProfile->website }}" target="_blank" class="adm-link">{{ $project->client->clientProfile->website }}</a>
          @else —
          @endif
        </div>
      </div>
    </div>

    {{-- Snapshot financeiro --}}
    <div class="adm-section">
      <h2 class="adm-section-title">▶ SNAPSHOT FINANCEIRO (IMUTÁVEL)</h2>
      <div class="adm-snapshot-grid">
        <div class="adm-snapshot-item">
          <span class="adm-snap-label">Pacote</span>
          <span class="adm-snap-value">{{ $project->agreed_package_name }} v{{ $project->agreed_package_version }}</span>
        </div>
        <div class="adm-snapshot-item">
          <span class="adm-snap-label">Valor Base</span>
          <span class="adm-snap-value">R$ {{ number_format($project->agreed_base_value, 2, ',', '.') }}</span>
        </div>
        <div class="adm-snapshot-item">
          <span class="adm-snap-label">Desconto</span>
          <span class="adm-snap-value adm-value--red">- R$ {{ number_format($project->agreed_discount_value, 2, ',', '.') }}</span>
        </div>
        <div class="adm-snapshot-item adm-snapshot-item--highlight">
          <span class="adm-snap-label">Valor Final</span>
          <span class="adm-snap-value adm-value--green">R$ {{ number_format($project->agreed_final_value, 2, ',', '.') }}</span>
        </div>
        <div class="adm-snapshot-item">
          <span class="adm-snap-label">Taxa 20%</span>
          <span class="adm-snap-value">R$ {{ number_format($project->guildabyte_fee_value, 2, ',', '.') }}</span>
        </div>
        <div class="adm-snapshot-item">
          <span class="adm-snap-label">Líquido Equipe</span>
          <span class="adm-snap-value adm-value--green">R$ {{ number_format($project->team_net_value, 2, ',', '.') }}</span>
        </div>
      </div>
    </div>

    {{-- Briefing completo --}}
    <div class="adm-section">
      <h2 class="adm-section-title">▶ BRIEFING DO CLIENTE</h2>
      <div class="adm-briefing-text">
        {!! nl2br(e($project->description)) !!}
      </div>
    </div>

    {{-- Proposta existente (se houver) --}}
    @if($proposal)
    <div class="adm-section adm-section--proposal">
      <h2 class="adm-section-title">▶ PROPOSTA ENVIADA</h2>
      <div class="adm-proposal-data">
        <div><span class="adm-label">Valor Proposto:</span>
          <strong class="adm-value--green">R$ {{ number_format($proposal->proposed_value, 2, ',', '.') }}</strong>
        </div>
        <div><span class="adm-label">Prazo:</span> {{ $proposal->proposed_deadline_days }} dias úteis</div>
        <div><span class="adm-label">Status:</span>
          <span class="adm-status-badge adm-status--{{ $proposal->status }}">{{ strtoupper($proposal->status) }}</span>
        </div>
        <div class="adm-proposal-scope">
          <strong>Escopo:</strong><br>
          {!! nl2br(e($proposal->scope)) !!}
        </div>
        @if($proposal->includes)
        <div class="adm-proposal-list">
          <strong>✓ Inclui:</strong>
          <ul>@foreach($proposal->includes as $item)<li>{{ $item }}</li>@endforeach</ul>
        </div>
        @endif
        @if($proposal->excludes)
        <div class="adm-proposal-list">
          <strong>✗ Não Inclui:</strong>
          <ul>@foreach($proposal->excludes as $item)<li>{{ $item }}</li>@endforeach</ul>
        </div>
        @endif
      </div>
    </div>
    @endif

    {{-- Aceite do cliente (se houver) --}}
    @if($acceptance)
    <div class="adm-section adm-section--accepted">
      <h2 class="adm-section-title">▶ ACEITE DIGITAL REGISTRADO ✓</h2>
      <div>
        <div><span class="adm-label">Data:</span> {{ $acceptance->accepted_at->format('d/m/Y \à\s H:i') }}</div>
        <div><span class="adm-label">IP:</span> {{ $acceptance->ip_address }}</div>
        <div><span class="adm-label">Dispositivo:</span> {{ Str::limit($acceptance->user_agent, 80) }}</div>
      </div>
    </div>
    @endif

  </div>

  {{-- ── COLUNA DIREITA: Painel de Ações ── --}}
  <div class="adm-actions-panel">

    {{-- Transição de Status --}}
    <div class="adm-action-box">
      <h3 class="adm-action-title">⚡ MOVER STATUS</h3>
      <p class="adm-action-desc">Status atual: <strong class="adm-value--green">{{ strtoupper(str_replace('_', ' ', $project->status)) }}</strong></p>

      @php
        $transitions = [
          'received'          => [['value' => 'in_analysis', 'label' => '🔍 Iniciar Análise', 'color' => 'yellow']],
          'in_analysis'       => [['value' => 'in_analysis', 'label' => '(crie a proposta →)', 'color' => 'grey', 'disabled' => true]],
          'proposal_sent'     => [],
          'proposal_accepted' => [['value' => 'in_progress', 'label' => '⚙ Iniciar Desenvolvimento', 'color' => 'green']],
          'in_progress'       => [
            ['value' => 'testing',  'label' => '🧪 Mover para Testes', 'color' => 'yellow'],
            ['value' => 'delivered','label' => '🚀 Marcar como Entregue', 'color' => 'green'],
          ],
          'testing'           => [['value' => 'delivered', 'label' => '🚀 Marcar como Entregue', 'color' => 'green']],
          'delivered'         => [['value' => 'maintenance', 'label' => '🔧 Iniciar Manutenção', 'color' => 'purple']],
        ];
        $availableTransitions = $transitions[$project->status] ?? [];
      @endphp

      @if(!empty($availableTransitions))
        @foreach($availableTransitions as $t)
          @if(empty($t['disabled']))
          <form method="POST" action="{{ route('admin.projects.status', $project) }}" class="adm-form-inline">
            @csrf
            <input type="hidden" name="status" value="{{ $t['value'] }}">
            <button type="submit" class="adm-action-btn adm-btn--{{ $t['color'] }}">{{ $t['label'] }}</button>
          </form>
          @else
          <div class="adm-action-btn adm-btn--grey adm-btn--disabled">{{ $t['label'] }}</div>
          @endif
        @endforeach
      @else
        <p class="adm-action-note">Nenhuma transição disponível neste status.</p>
      @endif

      {{-- Cancelar sempre disponível --}}
      @if(!in_array($project->status, ['delivered', 'maintenance', 'cancelled']))
      <form method="POST" action="{{ route('admin.projects.status', $project) }}" class="adm-form-inline"
        onsubmit="return confirm('Cancelar projeto? Esta ação não pode ser desfeita facilmente.')">
        @csrf
        <input type="hidden" name="status" value="cancelled">
        <button type="submit" class="adm-action-btn adm-btn--red adm-btn--sm">✗ Cancelar Projeto</button>
      </form>
      @endif
    </div>

    {{-- Criar Proposta (só se não houver proposta ativa e status elegível) --}}
    @if(!$proposal && in_array($project->status, ['received', 'in_analysis']))
    <div class="adm-action-box adm-action-box--proposal">
      <h3 class="adm-action-title">📋 CRIAR PROPOSTA FORMAL</h3>
      <p class="adm-action-desc">Define o escopo, valor e prazo para o cliente aceitar digitalmente.</p>

      <form method="POST" action="{{ route('admin.projects.proposal.store', $project) }}" id="form-proposal">
        @csrf

        <div class="adm-field">
          <label class="adm-label">Escopo do Projeto</label>
          <textarea name="scope" class="adm-textarea" rows="5" required placeholder="Descreva o que será desenvolvido..."></textarea>
        </div>

        <div class="adm-field">
          <label class="adm-label">Valor Proposto (R$)</label>
          <input type="number" name="proposed_value" class="adm-input"
            step="0.01" min="0"
            value="{{ old('proposed_value', $project->agreed_base_value) }}" required>
          <span class="adm-hint">Base: R$ {{ number_format($project->agreed_base_value, 2, ',', '.') }}</span>
        </div>

        <div class="adm-field">
          <label class="adm-label">Prazo (dias úteis)</label>
          <input type="number" name="proposed_deadline_days" class="adm-input"
            min="1" max="730"
            value="{{ old('proposed_deadline_days', 30) }}" required>
        </div>

        <div class="adm-field">
          <label class="adm-label">O que INCLUI (1 item por linha)</label>
          <textarea name="includes_raw" class="adm-textarea" rows="3"
            placeholder="Design responsivo&#10;Painel administrativo&#10;Integração com WhatsApp"
            onchange="syncList('includes_raw', 'includes_json')"></textarea>
          <input type="hidden" name="includes" id="includes_json">
        </div>

        <div class="adm-field">
          <label class="adm-label">O que NÃO INCLUI (1 item por linha)</label>
          <textarea name="excludes_raw" class="adm-textarea" rows="3"
            placeholder="App mobile&#10;Integração com ERP&#10;Hospedagem"
            onchange="syncList('excludes_raw', 'excludes_json')"></textarea>
          <input type="hidden" name="excludes" id="excludes_json">
        </div>

        <button type="submit" class="adm-action-btn adm-btn--green adm-btn--full"
          onclick="syncList('includes_raw','includes_json'); syncList('excludes_raw','excludes_json')">
          📤 ENVIAR PROPOSTA AO CLIENTE
        </button>
      </form>
    </div>
    @endif

    {{-- Info: proposta já existe --}}
    @if($proposal && $proposal->status === 'pending')
    <div class="adm-action-box adm-action-box--info">
      <h3 class="adm-action-title">⏳ AGUARDANDO ACEITE</h3>
      <p class="adm-action-desc">Proposta enviada em {{ $proposal->created_at->format('d/m/Y') }}. O cliente precisa aceitar digitalmente no painel dele.</p>
    </div>
    @endif

    {{-- Aditivo de Escopo (disponível quando em desenvolvimento ou testes) --}}
    @if(in_array($project->status, ['in_progress', 'testing', 'proposal_accepted']))
    <div class="adm-action-box" style="border-color: #ff8c42;">
      <h3 class="adm-action-title" style="color: #ff8c42;">📝 ADITIVO DE ESCOPO</h3>
      <p class="adm-action-desc">Adiciona custo/prazo extra ao projeto com aceite formal do cliente. O valor final acordado NÃO é recalculado automaticamente.</p>

      <form method="POST" action="{{ route('admin.projects.addendum.store', $project) }}" id="form-addendum">
        @csrf

        <div class="adm-field">
          <label class="adm-label">Título do Aditivo</label>
          <input type="text" name="title" class="adm-input"
            placeholder="Ex: Integração com ERP adicional"
            required maxlength="255">
        </div>

        <div class="adm-field">
          <label class="adm-label">Descrição Detalhada</label>
          <textarea name="description" class="adm-textarea" rows="3"
            placeholder="Descreva o que será acrescido ao escopo..." required></textarea>
        </div>

        <div class="adm-field">
          <label class="adm-label">Custo Adicional (R$)</label>
          <input type="number" name="additional_cost" class="adm-input"
            step="0.01" min="0" placeholder="0.00" required>
        </div>

        <div class="adm-field">
          <label class="adm-label">Prazo Adicional (dias úteis)</label>
          <input type="number" name="additional_days" class="adm-input"
            min="0" placeholder="0" required>
        </div>

        <button type="submit" class="adm-action-btn adm-btn--full"
          style="background:#1a1000; color:#ff8c42; border-color:#ff8c42;"
          onclick="return confirm('Enviar aditivo de escopo para aceite do cliente?')">
          📤 ENVIAR PARA ACEITE DO CLIENTE
        </button>
      </form>
    </div>
    @endif

  </div>
</div>

@push('scripts')
<script>
function syncList(rawId, hiddenId) {
  const raw = document.querySelector('[name="' + rawId + '"]');
  const hidden = document.getElementById(hiddenId);
  if (!raw || !hidden) return;
  const items = raw.value.split('\n').map(s => s.trim()).filter(s => s.length > 0);
  hidden.value = JSON.stringify(items);
}
// Converte includes/excludes array para o formato que o Laravel espera (name="includes[]")
document.getElementById('form-proposal')?.addEventListener('submit', function(e) {
  ['includes', 'excludes'].forEach(key => {
    const rawEl = document.querySelector('[name="' + key + '_raw"]');
    const hiddenEl = document.getElementById(key + '_json');
    if (!rawEl || !hiddenEl) return;
    const items = rawEl.value.split('\n').map(s => s.trim()).filter(s => s);
    // Remove hidden, add proper array inputs
    hiddenEl.remove();
    items.forEach((item, i) => {
      const inp = document.createElement('input');
      inp.type = 'hidden';
      inp.name = key + '[]';
      inp.value = item;
      this.appendChild(inp);
    });
  });
});
</script>
@endpush

@endsection

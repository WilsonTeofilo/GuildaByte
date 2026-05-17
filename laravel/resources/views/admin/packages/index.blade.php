@extends('layouts.admin')
@section('title', 'Pacotes e Versões')

@section('content')

<div class="adm-page-header">
  <h1 class="adm-title">📦 PACOTES BASE</h1>
  <p class="adm-subtitle">Preços alterados geram nova versão — nunca alteram projetos em andamento</p>
</div>

@if(session('success'))
  <div class="adm-alert adm-alert--success">✓ {{ session('success') }}</div>
@endif
@if($errors->any())
  <div class="adm-alert adm-alert--error">
    @foreach($errors->all() as $e) <div>✗ {{ $e }}</div> @endforeach
  </div>
@endif

{{-- ── GRID DOIS PAINÉIS: Criar | Listar ── --}}
<div class="adm-pkg-grid">

  {{-- PAINEL ESQUERDO: Novo Pacote --}}
  <div class="adm-action-box adm-action-box--proposal">
    <h3 class="adm-action-title">➕ CRIAR NOVO PACOTE</h3>
    <p class="adm-action-desc">Define nome, slug e preço base. Um pacote começa na versão 1.</p>

    <form method="POST" action="{{ route('admin.packages.store') }}" id="form-create-package">
      @csrf

      <div class="adm-field">
        <label class="adm-label">Nome do Pacote</label>
        <input type="text" name="name" class="adm-input"
          placeholder="Ex: Core, Start, Custom"
          value="{{ old('name') }}" required maxlength="100">
      </div>

      <div class="adm-field">
        <label class="adm-label">Slug (identificador único)</label>
        <input type="text" name="slug" class="adm-input"
          placeholder="Ex: core, start, custom"
          value="{{ old('slug') }}" required maxlength="50"
          pattern="[a-z0-9\-]+" title="Apenas letras minúsculas, números e hífens">
        <span class="adm-hint">Usado internamente — ex: start, core, custom</span>
      </div>

      <div class="adm-field">
        <label class="adm-label">Preço Base (R$)</label>
        <input type="number" name="base_price" class="adm-input"
          step="0.01" min="0" max="999999"
          placeholder="1500.00" value="{{ old('base_price') }}" required>
      </div>

      <div class="adm-field">
        <label class="adm-label">Funcionalidades (1 por linha)</label>
        <textarea name="features_raw" class="adm-textarea" rows="4"
          placeholder="Design responsivo&#10;Painel administrativo&#10;Integração WhatsApp">{{ old('features_raw') }}</textarea>
        <span class="adm-hint">Opcional — descreve o que está incluso neste pacote</span>
      </div>

      <button type="submit" class="adm-action-btn adm-btn--green adm-btn--full"
        onclick="syncFeatures()">
        📦 CRIAR PACOTE (v1)
      </button>
    </form>
  </div>

  {{-- PAINEL DIREITO: Pacotes Existentes --}}
  <div>
    <h2 class="adm-section-title">▶ PACOTES ATIVOS ({{ $packages->count() }})</h2>

    @forelse($packages as $package)
    <div class="adm-section adm-section--pkg">

      {{-- Header do Pacote --}}
      <div class="adm-pkg-header">
        <div>
          <div class="adm-pkg-name">{{ $package->name }}</div>
          <div class="adm-pkg-slug">slug: {{ $package->slug }}</div>
        </div>
        @if($package->currentVersion)
        <div class="adm-pkg-version-badge">
          v{{ $package->currentVersion->version_number }}
          <span class="adm-pkg-price">R$ {{ number_format($package->currentVersion->base_price, 2, ',', '.') }}</span>
        </div>
        @endif
      </div>

      {{-- Formulário de Versionamento --}}
      <form method="POST" action="{{ route('admin.packages.update', $package) }}" class="adm-pkg-update-form">
        @csrf
        @method('PUT')

        <div class="adm-pkg-update-row">
          <div class="adm-field" style="flex:1">
            <label class="adm-label">Novo Nome</label>
            <input type="text" name="name" class="adm-input" value="{{ $package->name }}" required maxlength="100">
          </div>
          <div class="adm-field" style="flex:1">
            <label class="adm-label">
              Novo Preço →
              <span class="adm-value--yellow">Vai criar v{{ $package->currentVersion ? $package->currentVersion->version_number + 1 : 1 }}</span>
            </label>
            <input type="number" name="base_price" class="adm-input"
              step="0.01" min="0"
              value="{{ $package->currentVersion ? $package->currentVersion->base_price : '' }}" required>
          </div>
        </div>

        {{-- Feature hidden obrigatório pro FormRequest --}}
        <input type="hidden" name="features[]" value="Incluso no pacote {{ $package->name }}">

        <button type="submit" class="adm-action-btn adm-btn--yellow adm-btn--sm"
          onclick="return confirm('Versionar {{ $package->name }}? O preço atual de projetos em andamento NÃO será alterado.')">
          ⚡ SALVAR E VERSIONAR
        </button>
      </form>

    </div>
    @empty
      <div class="adm-empty">Nenhum pacote cadastrado ainda. Crie o primeiro!</div>
    @endforelse
  </div>

</div>

@push('scripts')
<script>
function syncFeatures() {
  const raw = document.querySelector('[name="features_raw"]');
  if (!raw) return;
  const form = document.getElementById('form-create-package');
  // Remove inputs antigos
  form.querySelectorAll('input[name="features[]"]').forEach(el => el.remove());
  const items = raw.value.split('\n').map(s => s.trim()).filter(s => s.length > 0);
  items.forEach(item => {
    const inp = document.createElement('input');
    inp.type = 'hidden';
    inp.name = 'features[]';
    inp.value = item;
    form.appendChild(inp);
  });
}
</script>
@endpush

@endsection

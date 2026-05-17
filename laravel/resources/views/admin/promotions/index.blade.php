@extends('layouts.admin')
@section('title', 'Promoções e Cupons')

@section('content')

<div class="adm-page-header">
  <h1 class="adm-title">🏷️ PROMOÇÕES</h1>
  <p class="adm-subtitle">Cupons de desconto — afetam APENAS novos pedidos. Projetos em andamento não são alterados.</p>
</div>

@if(session('success'))
  <div class="adm-alert adm-alert--success">✓ {{ session('success') }}</div>
@endif
@if($errors->any())
  <div class="adm-alert adm-alert--error">
    @foreach($errors->all() as $e) <div>✗ {{ $e }}</div> @endforeach
  </div>
@endif

<div class="adm-pkg-grid">

  {{-- PAINEL ESQUERDO: Criar Promoção --}}
  <div class="adm-action-box" style="border-color: #ffe135;">
    <h3 class="adm-action-title" style="color: #ffe135;">➕ CRIAR NOVA PROMOÇÃO</h3>
    <p class="adm-action-desc">Gera um cupom que o cliente aplica ao abrir um novo pedido.</p>

    <form method="POST" action="{{ route('admin.promotions.store') }}">
      @csrf

      <div class="adm-field">
        <label class="adm-label">Código do Cupom</label>
        <input type="text" name="code" class="adm-input"
          placeholder="Ex: BLACKFRIDAY, LANCAMENTO10"
          value="{{ old('code') }}" required maxlength="50"
          style="text-transform:uppercase; letter-spacing: 0.1em;">
        <span class="adm-hint">Será exibido ao cliente exatamente como digitado</span>
      </div>

      <div class="adm-field">
        <label class="adm-label">Descrição da Campanha</label>
        <input type="text" name="description" class="adm-input"
          placeholder="Ex: Black Friday 2025 — 15% off"
          value="{{ old('description') }}" required maxlength="200">
      </div>

      <div class="adm-field">
        <label class="adm-label">Tipo de Desconto</label>
        <select name="discount_type" class="adm-input" required>
          <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>
            Porcentagem (%)
          </option>
          <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>
            Valor Fixo (R$)
          </option>
        </select>
      </div>

      <div class="adm-field">
        <label class="adm-label">Valor do Desconto</label>
        <input type="number" name="discount_value" class="adm-input"
          step="0.01" min="0.01" max="99999"
          placeholder="15 (para 15%) ou 300 (para R$300)"
          value="{{ old('discount_value') }}" required>
      </div>

      <button type="submit" class="adm-action-btn adm-btn--yellow adm-btn--full">
        🏷️ GERAR CUPOM
      </button>
    </form>
  </div>

  {{-- PAINEL DIREITO: Promoções Existentes --}}
  <div>
    <h2 class="adm-section-title">▶ CUPONS CADASTRADOS ({{ $promotions->count() }})</h2>

    @forelse($promotions as $promo)
    <div class="adm-section adm-promo-card {{ $promo->is_active ? '' : 'adm-promo-card--inactive' }}">

      <div class="adm-promo-header">
        <div>
          <div class="adm-promo-code">{{ $promo->code }}</div>
          <div class="adm-promo-desc">{{ $promo->description }}</div>
        </div>
        <div class="adm-promo-badge {{ $promo->is_active ? 'adm-promo-badge--active' : 'adm-promo-badge--off' }}">
          {{ $promo->is_active ? 'ATIVO' : 'OFF' }}
        </div>
      </div>

      <div class="adm-promo-value">
        @if($promo->discount_type === 'percentage')
          <span class="adm-value--green">{{ number_format($promo->discount_value, 0, ',', '.') }}% OFF</span>
        @else
          <span class="adm-value--green">R$ {{ number_format($promo->discount_value, 2, ',', '.') }} OFF</span>
        @endif
        <span class="adm-promo-type">
          ({{ $promo->discount_type === 'percentage' ? 'porcentagem' : 'valor fixo' }})
        </span>
      </div>

      <form method="POST" action="{{ route('admin.promotions.toggle', $promo) }}" class="adm-form-inline">
        @csrf
        <button type="submit"
          class="adm-action-btn adm-btn--sm {{ $promo->is_active ? 'adm-btn--red' : 'adm-btn--green' }}">
          {{ $promo->is_active ? '⏸ DESATIVAR CUPOM' : '▶ ATIVAR CUPOM' }}
        </button>
      </form>

    </div>
    @empty
      <div class="adm-empty">Nenhuma promoção cadastrada. Crie a primeira campanha!</div>
    @endforelse
  </div>

</div>

@endsection

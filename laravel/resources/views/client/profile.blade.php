@extends('layouts.client')

@section('title', 'Meu Perfil')

@push('styles')
    @vite(['resources/css/profile.css'])
@endpush

@section('content')

<div class="profile-header">
  <h1 class="profile-title">MEU PERFIL</h1>
  <p class="profile-subtitle">Configurações da conta e consentimentos LGPD</p>
</div>

@if(session('success'))
  <div class="profile-success">
    ✓ {{ session('success') }}
  </div>
@endif

@if($errors->any())
  <div class="profile-error">
    @foreach($errors->all() as $error)
      <div>✗ {{ $error }}</div>
    @endforeach
  </div>
@endif

<form method="POST" action="{{ route('client.profile.update') }}">
  @csrf
  <div class="profile-grid">

    {{-- DADOS PESSOAIS --}}
    <div class="profile-section">
      <h2>&#9654; IDENTIDADE</h2>

      <div class="pf-group">
        <label class="pf-label" for="pf-name">Nome</label>
        <input class="pf-input" id="pf-name" type="text" value="{{ $user->name }}" disabled title="Altere o nome via suporte">
      </div>
      <div class="pf-group">
        <label class="pf-label" for="pf-email">E-mail</label>
        <input class="pf-input" id="pf-email" type="email" value="{{ $user->email }}" disabled title="Altere o e-mail via suporte">
      </div>
      <div class="pf-group">
        <label class="pf-label" for="pf-phone">WhatsApp</label>
        <input class="pf-input" id="pf-phone" name="phone" type="tel"
          maxlength="20"
          placeholder="(11) 99999-9999"
          value="{{ old('phone', $profile?->phone) }}">
      </div>
    </div>

    {{-- NEGÓCIO --}}
    <div class="profile-section">
      <h2>&#9654; SEU NEGÓCIO</h2>

      <div class="pf-group">
        <label class="pf-label" for="pf-business-name">Nome do Negócio</label>
        <input class="pf-input" id="pf-business-name" name="business_name" type="text"
          maxlength="100"
          placeholder="Ex: Barbearia do Wilson"
          value="{{ old('business_name', $profile?->business_name) }}">
      </div>
      <div class="pf-group">
        <label class="pf-label" for="pf-instagram">Instagram</label>
        <input class="pf-input" id="pf-instagram" name="instagram" type="text"
          maxlength="100"
          placeholder="@seuarroba"
          value="{{ old('instagram', $profile?->instagram) }}">
      </div>
      <div class="pf-group">
        <label class="pf-label" for="pf-website">Website</label>
        <input class="pf-input" id="pf-website" name="website" type="url"
          maxlength="255"
          placeholder="https://seusite.com.br"
          value="{{ old('website', $profile?->website) }}">
      </div>
    </div>

    {{-- LGPD / CONSENTIMENTOS --}}
    <div class="profile-section full">
      <h2>&#9654; CONSENTIMENTOS LGPD</h2>

      <div class="check-row">
        <input type="checkbox" id="marketing_email" name="marketing_email" value="1"
          {{ old('marketing_email', $profile?->marketing_email) ? 'checked' : '' }}>
        <label for="marketing_email">Aceito receber ofertas, novidades e promoções por <strong>e-mail</strong>. (Opcional — pode revogar a qualquer momento)</label>
      </div>
      <div class="check-row">
        <input type="checkbox" id="marketing_whatsapp" name="marketing_whatsapp" value="1"
          {{ old('marketing_whatsapp', $profile?->marketing_whatsapp) ? 'checked' : '' }}>
        <label for="marketing_whatsapp">Aceito receber comunicações por <strong>WhatsApp</strong>. (Opcional — pode revogar a qualquer momento)</label>
      </div>

      <p class="lgpd-text">
        Seus dados são tratados conforme a <strong>Lei Geral de Proteção de Dados (LGPD — Lei nº 13.709/2018)</strong>.
        Para solicitar exclusão total dos seus dados, entre em contato pelo suporte.
      </p>

      <button class="pf-submit mt-15" type="submit">SALVAR ALTERAÇÕES</button>
    </div>

    {{-- ZONA DE PERIGO --}}
    <div class="profile-section full danger-zone">
      <h2>&#9654; ZONA DE PERIGO</h2>
      <p>A exclusão da conta é permanente e irrecuperável. Todos os seus projetos e dados serão arquivados conforme nossa política de retenção LGPD.</p>
      <button type="button" class="danger-btn" onclick="if(confirm('Tem certeza? Esta ação é IRREVERSÍVEL.')) alert('Entre em contato com o suporte para solicitar a exclusão.')">
        SOLICITAR EXCLUSÃO DE CONTA
      </button>
    </div>

  </div>
</form>
@endsection

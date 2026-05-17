@extends('layouts.client')

@section('title', 'Meu Perfil')

@section('content')
<style>
  .profile-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    max-width: 760px;
    margin: 0 auto;
  }
  @media (max-width: 600px) { .profile-grid { grid-template-columns: 1fr; } }

  .profile-section {
    background: #0a0a10;
    border: 2px solid var(--gb-border);
    border-radius: 8px;
    padding: 1.5rem;
  }
  .profile-section h2 {
    font-family: 'Press Start 2P', monospace;
    font-size: 0.65rem;
    color: var(--gb-green);
    margin-bottom: 1.2rem;
    letter-spacing: 1px;
  }
  .profile-section.full { grid-column: 1 / -1; }

  .pf-group { margin-bottom: 1rem; }
  .pf-label {
    display: block;
    font-family: 'Press Start 2P', monospace;
    font-size: 0.5rem;
    color: var(--gb-muted);
    margin-bottom: 4px;
    text-transform: uppercase;
  }
  .pf-input {
    width: 100%;
    background: #101018;
    border: 1px solid var(--gb-border);
    border-radius: 4px;
    padding: 0.6rem 0.8rem;
    color: var(--gb-text);
    font-size: 0.9rem;
    transition: border-color 0.2s;
    box-sizing: border-box;
  }
  .pf-input:focus { outline: none; border-color: var(--gb-purple); }

  .check-row { display: flex; align-items: flex-start; gap: 8px; margin-bottom: 8px; }
  .check-row input[type="checkbox"] { margin-top: 3px; accent-color: var(--gb-green); }
  .check-row label { font-size: 0.8rem; color: var(--gb-muted); line-height: 1.4; }

  .pf-submit {
    background: var(--gb-purple);
    color: #fff;
    border: none;
    padding: 0.7rem 1.5rem;
    font-family: 'Press Start 2P', monospace;
    font-size: 0.6rem;
    border-radius: 4px;
    cursor: pointer;
    transition: opacity 0.2s;
    width: 100%;
  }
  .pf-submit:hover { opacity: 0.85; }

  .danger-zone {
    background: rgba(255, 77, 77, 0.05);
    border-color: rgba(255, 77, 77, 0.3);
  }
  .danger-zone h2 { color: var(--gb-danger); }
  .danger-zone p { font-size: 0.75rem; color: var(--gb-muted); margin-bottom: 1rem; }
  .danger-btn {
    background: transparent;
    border: 1px solid var(--gb-danger);
    color: var(--gb-danger);
    padding: 0.6rem 1rem;
    font-family: 'Press Start 2P', monospace;
    font-size: 0.55rem;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
  }
  .danger-btn:hover { background: var(--gb-danger); color: #fff; }
</style>

<div style="max-width: 760px; margin: 0 auto 2rem;">
  <h1 style="font-family: 'Press Start 2P', monospace; font-size: 1.1rem; color: var(--gb-green); margin-bottom: 0.3rem;">MEU PERFIL</h1>
  <p style="color: var(--gb-muted); font-size: 0.8rem; font-family: monospace;">Configurações da conta e consentimentos LGPD</p>
</div>

@if(session('success'))
  <div style="background: rgba(146,255,203,0.1); border: 1px solid var(--gb-green); color: var(--gb-green); padding: 0.8rem 1rem; border-radius: 6px; font-family: monospace; font-size: 0.85rem; margin-bottom: 1.5rem; max-width: 760px; margin-left: auto; margin-right: auto;">
    ✓ {{ session('success') }}
  </div>
@endif

@if($errors->any())
  <div style="background: rgba(255,77,77,0.1); border: 1px solid var(--gb-danger); color: var(--gb-danger); padding: 0.8rem 1rem; border-radius: 6px; font-size: 0.8rem; margin-bottom: 1.5rem; max-width: 760px; margin-left: auto; margin-right: auto;">
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

      <p style="font-size: 0.7rem; color: var(--gb-muted); margin-top: 1rem; border-top: 1px dashed var(--gb-border); padding-top: 1rem;">
        Seus dados são tratados conforme a <strong>Lei Geral de Proteção de Dados (LGPD — Lei nº 13.709/2018)</strong>.
        Para solicitar exclusão total dos seus dados, entre em contato pelo suporte.
      </p>

      <button class="pf-submit" type="submit" style="margin-top: 1.5rem;">SALVAR ALTERAÇÕES</button>
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

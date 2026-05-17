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
  <div class="profile-success">✓ {{ session('success') }}</div>
@endif
@if($errors->any())
  <div class="profile-error">
    @foreach($errors->all() as $error) <div>✗ {{ $error }}</div> @endforeach
  </div>
@endif

{{-- ══ DADOS DO PERFIL ══ --}}
<form method="POST" action="{{ route('client.profile.update') }}">
  @csrf
  <div class="profile-grid">

    <div class="profile-section">
      <h2>&#9654; IDENTIDADE</h2>

      <div class="pf-group">
        <label class="pf-label">Nome</label>
        <input class="pf-input" type="text" value="{{ $user->name }}" disabled title="Altere via suporte">
        <span class="pf-hint">Alteração somente via suporte.</span>
      </div>
      <div class="pf-group">
        <label class="pf-label">E-mail (login)</label>
        <input class="pf-input" type="email" value="{{ $user->email }}" disabled>
        <span class="pf-hint">O e-mail é a chave de acesso e não pode ser alterado.</span>
      </div>
      <div class="pf-group">
        <label class="pf-label" for="pf-phone">WhatsApp</label>
        <input class="pf-input" id="pf-phone" name="phone" type="tel"
          maxlength="20" placeholder="(11) 99999-9999"
          value="{{ old('phone', $profile?->phone) }}">
      </div>
    </div>

    <div class="profile-section">
      <h2>&#9654; SEU NEGÓCIO</h2>

      <div class="pf-group">
        <label class="pf-label" for="pf-business-name">Nome do Negócio</label>
        <input class="pf-input" id="pf-business-name" name="business_name" type="text"
          maxlength="100" placeholder="Ex: Barbearia do Wilson"
          value="{{ old('business_name', $profile?->business_name) }}">
      </div>
      <div class="pf-group">
        <label class="pf-label" for="pf-instagram">Instagram</label>
        <input class="pf-input" id="pf-instagram" name="instagram" type="text"
          maxlength="100" placeholder="@seuarroba"
          value="{{ old('instagram', $profile?->instagram) }}">
      </div>
      <div class="pf-group">
        <label class="pf-label" for="pf-website">Website</label>
        <input class="pf-input" id="pf-website" name="website" type="url"
          maxlength="255" placeholder="https://seusite.com.br"
          value="{{ old('website', $profile?->website) }}">
      </div>
    </div>

    <div class="profile-section full">
      <h2>&#9654; CONSENTIMENTOS LGPD</h2>
      <div class="check-row">
        <input type="checkbox" id="marketing_email" name="marketing_email" value="1"
          {{ old('marketing_email', $profile?->marketing_email) ? 'checked' : '' }}>
        <label for="marketing_email">Aceito receber ofertas por <strong>e-mail</strong>. (Opcional)</label>
      </div>
      <div class="check-row">
        <input type="checkbox" id="marketing_whatsapp" name="marketing_whatsapp" value="1"
          {{ old('marketing_whatsapp', $profile?->marketing_whatsapp) ? 'checked' : '' }}>
        <label for="marketing_whatsapp">Aceito receber comunicações por <strong>WhatsApp</strong>. (Opcional)</label>
      </div>
      <p class="lgpd-text">
        Dados tratados conforme a <strong>LGPD — Lei nº 13.709/2018</strong>.
      </p>
      <button class="pf-submit mt-15" type="submit">SALVAR ALTERAÇÕES</button>
    </div>

  </div>
</form>

{{-- ══ SEGURANÇA — TROCA DE SENHA VIA OTP ══ --}}
<div class="profile-grid mt-32">
  <div class="profile-section security-section full">
    <h2>&#9654; ALTERAR SENHA</h2>
    <p class="pf-security-desc">
      Para sua segurança, enviaremos um código de 6 dígitos para <strong>{{ $user->email }}</strong>.<br>
      O código expira em <strong>3 minutos</strong> e é destruído ao ser usado.
    </p>

    {{-- Passo 1: Solicitar código --}}
    <div class="pf-otp-steps">
      <div class="pf-otp-step" id="step-1">
        <h3 class="pf-step-title">① Solicitar código de verificação</h3>
        <form method="POST" action="{{ route('client.profile.password.otp') }}">
          @csrf
          <button type="submit" class="pf-submit pf-submit--security">📧 ENVIAR CÓDIGO POR E-MAIL</button>
        </form>
      </div>

      <div class="pf-otp-divider">▼</div>

      {{-- Passo 2: Confirmar código + nova senha --}}
      <div class="pf-otp-step" id="step-2">
        <h3 class="pf-step-title">② Inserir código e nova senha</h3>
        <form method="POST" action="{{ route('client.profile.password.change') }}">
          @csrf

          <div class="pf-group">
            <label class="pf-label" for="sec-otp">Código Recebido por E-mail</label>
            <input class="pf-input pf-input--otp" id="sec-otp" name="otp_code" type="text"
              maxlength="6" pattern="\d{6}" placeholder="000000"
              autocomplete="one-time-code" inputmode="numeric">
          </div>

          <div class="pf-group">
            <label class="pf-label" for="sec-new-pass">Nova Senha</label>
            <div class="pf-password-wrap">
              <input class="pf-input" id="sec-new-pass" name="new_password" type="password"
                placeholder="Mínimo 8 caracteres" autocomplete="new-password"
                oninput="checkPasswordStrength(this.value)">
              <button type="button" class="pf-eye" onclick="togglePassword('sec-new-pass', this)">👁</button>
            </div>
            <div class="pf-strength-bar"><div class="pf-strength-fill" id="strength-fill"></div></div>
            <span class="pf-strength-label" id="strength-label"></span>
          </div>

          <div class="pf-group">
            <label class="pf-label" for="sec-new-pass-confirm">Confirmar Nova Senha</label>
            <div class="pf-password-wrap">
              <input class="pf-input" id="sec-new-pass-confirm" name="new_password_confirmation" type="password"
                placeholder="Repita a nova senha" autocomplete="new-password">
              <button type="button" class="pf-eye" onclick="togglePassword('sec-new-pass-confirm', this)">👁</button>
            </div>
          </div>

          <button class="pf-submit pf-submit--security" type="submit">🔐 CONFIRMAR E ALTERAR SENHA</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- ══ ZONA DE PERIGO ══ --}}
<div class="profile-section full danger-zone mt-32">
  <h2>&#9654; ZONA DE PERIGO</h2>
  <p>A exclusão da conta é permanente e irrecuperável. Todos os dados serão arquivados conforme a política LGPD.</p>
  <button type="button" class="danger-btn"
    onclick="if(confirm('Tem certeza? Esta ação é IRREVERSÍVEL.')) alert('Entre em contato com o suporte para solicitar a exclusão.')">
    SOLICITAR EXCLUSÃO DE CONTA
  </button>
</div>

@push('scripts')
<script>
function togglePassword(id, btn) {
  const input = document.getElementById(id);
  const show = input.type === 'password';
  input.type = show ? 'text' : 'password';
  btn.textContent = show ? '🙈' : '👁';
}

function checkPasswordStrength(value) {
  const fill  = document.getElementById('strength-fill');
  const label = document.getElementById('strength-label');
  let score = 0;
  if (value.length >= 8)              score++;
  if (/[A-Z]/.test(value))            score++;
  if (/[0-9]/.test(value))            score++;
  if (/[^A-Za-z0-9]/.test(value))    score++;

  const levels = [
    { w: '25%',  color: '#ff4d4d', text: 'Fraca' },
    { w: '50%',  color: '#ff8c42', text: 'Razoável' },
    { w: '75%',  color: '#ffe135', text: 'Boa' },
    { w: '100%', color: '#92ffcb', text: 'Forte' },
  ];
  const lvl = value.length ? (levels[Math.max(0, score - 1)] ?? levels[0]) : null;
  fill.style.width      = lvl ? lvl.w : '0%';
  fill.style.background = lvl ? lvl.color : '';
  label.textContent     = lvl ? lvl.text : '';
  label.style.color     = lvl ? lvl.color : '';
}
</script>
@endpush

@endsection

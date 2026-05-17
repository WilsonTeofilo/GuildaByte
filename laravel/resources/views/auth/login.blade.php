<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | GuildaByte</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Pixelify+Sans:wght@500;600;700&family=Space+Grotesk:wght@500;700&family=Press+Start+2P&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/auth.js'])
  <link rel="stylesheet" href="{{ asset('style/design-system.css') }}?v=1.0">
  <link rel="stylesheet" href="{{ asset('style/auth.css') }}?v=1.5">
  <link rel="stylesheet" href="{{ asset('style/auth-helpers.css') }}?v=1.0">
  <link rel="stylesheet" href="{{ asset('style/auth_responsive.css') }}?v=1.0">
</head>
<body>
  <main class="gb-root">
    <div class="scanlines"></div>
    <div class="pixel-grid"></div>
    <div class="corner-deco tl"></div>
    <div class="corner-deco br"></div>
    <a class="auth-back-btn" href="{{ route('home') }}">&lt; VOLTAR</a>

    <svg class="pixel-stars" aria-hidden="true">
      <rect x="40" y="30" width="4" height="4" fill="#7F77DD" opacity="0.5"/>
      <rect x="82%" y="18%" width="4" height="4" fill="#92FFCB" opacity="0.45"/>
      <rect x="15%" y="84%" width="4" height="4" fill="#AFA9EC" opacity="0.35"/>
    </svg>

    <section class="os-window" aria-label="Login GuildaByte">
      <div class="os-titlebar">
        <span class="os-dot red"></span>
        <span class="os-dot yellow"></span>
        <span class="os-dot green"></span>
        <span class="os-title-text">LOGIN.EXE</span>
      </div>

      <div class="os-body">
        <div class="auth-logo-wrapper">
          <a class="auth-brand-link" href="{{ route('home') }}">
            <img src="{{ asset('assets/HeaderTrans.webp') }}" alt="" width="48" height="48" class="auth-logo-img">
            <span class="auth-logo-text">Guilda<span class="auth-logo-sub">Byte</span></span>
          </a>
        </div>

        @if(isset($selectedPlan))
        <div class="saved-plan">
          <span>PLANO SELECIONADO</span>
          <strong>{{ $selectedPlan['title'] }}</strong>
          <small>{{ $selectedPlan['price'] }}</small>
        </div>
        @endif

        {{-- Alertas de Sessão --}}
        @if(session('status'))
          <div class="auth-alert-success">
            ✓ {{ session('status') }}
          </div>
        @endif

        <!-- LOGIN TRADICIONAL -->
        <form id="formLoginDefault" method="post" action="{{ route('login') }}">
          @csrf
          <div class="fgrp">
            <label class="flbl" for="email">&#9654; EMAIL / LOGIN</label>
            <div class="input-wrap">
              <input class="pinp" id="email" name="email" type="email" placeholder="seuemail@exemplo.com" value="{{ old('email') }}" required autofocus>
            </div>
            @error('email')<div class="field-error-server auth-field-error">{{ $message }}</div>@enderror
          </div>

          <div class="fgrp auth-fgrp-mb">
            <label class="flbl" for="password">&#9654; SENHA</label>
            <div class="input-wrap">
              <input class="pinp" id="password" name="password" type="password" placeholder="********" required>
              <button class="toggle-password" type="button" data-toggle-password="#password" aria-label="Mostrar senha">S</button>
            </div>
            @error('password')<div class="field-error-server auth-field-error">{{ $message }}</div>@enderror
          </div>
          <button class="px-btn" type="submit">ENTRAR NO SISTEMA</button>
        </form>

        <!-- LOGIN OTP (Escondido por padrão) -->
        <form id="formLoginOtp" class="auth-otp-form" onsubmit="event.preventDefault(); return false;" data-send-url="{{ route('otp.send') }}" data-verify-url="{{ route('otp.verify') }}">
          <div class="fgrp" id="otpStep1">
            <label class="flbl" for="otpEmail">&#9654; EMAIL CADASTRADO</label>
            <div class="input-wrap">
              <input class="pinp" id="otpEmail" type="email" placeholder="seuemail@exemplo.com" required>
            </div>
            <button class="px-btn auth-btn-purple" type="button" id="btnSendOtp">ENVIAR CÓDIGO</button>
          </div>

          <div class="fgrp auth-otp-step2" id="otpStep2">
            <label class="flbl auth-otp-lbl" for="otpCode">&#9654; CÓDIGO (6 DÍGITOS)</label>
            <p class="auth-otp-help">Verifique seu e-mail. Expira em 3 min.</p>
            <div class="input-wrap">
              <input class="pinp auth-otp-input" id="otpCode" type="text" maxlength="6" placeholder="000000" required>
            </div>
            <div id="otpError" class="auth-otp-error"></div>
            <button class="px-btn auth-btn-green" type="button" id="btnVerifyOtp">CONFIRMAR CÓDIGO</button>
          </div>
        </form>

        <div class="auth-toggle-box">
            <button type="button" id="toggleAuthMode" class="auth-toggle-btn">
                [ Login sem senha (token) ]
            </button>
        </div>

        <div class="divider-row">
          <div class="divider-line"></div>
          <span class="divider-txt">OU</span>
          <div class="divider-line"></div>
        </div>

        <div class="google-btn-disabled">
          <svg width="14" height="14" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
          GOOGLE AUTH — EM BREVE
        </div>

        <div class="links-row">
          <a class="px-link" href="{{ route('register') }}">&#9654; CRIAR CONTA</a>
          <a class="px-link" href="#">&#9654; ESQUECI A SENHA</a>
        </div>
      </div>
    </section>

  </main>
</body>
</html>

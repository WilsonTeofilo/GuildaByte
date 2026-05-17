<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Esqueci a Senha | GuildaByte</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Pixelify+Sans:wght@500;600;700&family=Press+Start+2P&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="{{ asset('style/design-system.css') }}?v=1.0">
  <link rel="stylesheet" href="{{ asset('style/auth.css') }}?v=1.5">
  <link rel="stylesheet" href="{{ asset('style/auth-helpers.css') }}?v=1.0">
</head>
<body>
  <main class="gb-root">
    <div class="scanlines"></div>
    <div class="pixel-grid"></div>
    <div class="corner-deco tl"></div>
    <div class="corner-deco br"></div>
    <a class="auth-back-btn" href="{{ route('login') }}">&lt; VOLTAR</a>

    <section class="os-window" aria-label="Recuperação de Senha GuildaByte">
      <div class="os-titlebar">
        <span class="os-dot red"></span>
        <span class="os-dot yellow"></span>
        <span class="os-dot green"></span>
        <span class="os-title-text">ESQUECI_SENHA.EXE</span>
      </div>

      <div class="os-body">
        <div class="auth-logo-wrapper">
          <a class="auth-brand-link" href="{{ route('home') }}">
            <img src="{{ asset('assets/HeaderTrans.webp') }}" alt="" width="60" height="60" class="auth-logo-img">
            <span class="auth-logo-text">Guilda<span class="auth-logo-sub">Byte</span></span>
          </a>
        </div>

        @if(session('status'))
          <div class="auth-alert-success">✓ {{ session('status') }}</div>
        @endif

        @if($errors->any())
          <div class="auth-alert-error">✗ {{ $errors->first() }}</div>
        @endif

        <p style="color:var(--gb-muted);font-size:12px;margin-bottom:20px;font-family:monospace;">
          Informe o e-mail cadastrado. Enviaremos um link para redefinir sua senha.
        </p>

        <form method="POST" action="{{ route('password.email') }}">
          @csrf
          <div class="fgrp auth-fgrp-mb">
            <label class="flbl" for="email">&#9654; E-MAIL CADASTRADO</label>
            <div class="input-wrap">
              <input class="pinp" id="email" name="email" type="email"
                     placeholder="seuemail@exemplo.com"
                     value="{{ old('email') }}"
                     required autofocus maxlength="255">
            </div>
          </div>
          <button class="px-btn" type="submit">ENVIAR LINK DE RECUPERAÇÃO</button>
        </form>

        <div class="links-row" style="margin-top:20px;">
          <a class="px-link" href="{{ route('login') }}">&#9654; VOLTAR AO LOGIN</a>
        </div>
      </div>
    </section>
  </main>
</body>
</html>

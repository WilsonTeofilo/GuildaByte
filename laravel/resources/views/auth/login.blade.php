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
  <link rel="stylesheet" href="{{ asset('style/auth.css') }}?v=1.3">
  <link rel="stylesheet" href="{{ asset('style/auth_responsive.css') }}?v=1.0">
</head>
<body>
  <main class="gb-root">
    <div class="scanlines"></div>
    <div class="pixel-grid"></div>
    <div class="corner-deco tl"></div>
    <div class="corner-deco br"></div>
    <a class="auth-back-btn" href="{{ route('home') }}">&lt; VOLTAR AO SITE</a>

    <svg class="pixel-stars" aria-hidden="true">
      <rect x="40" y="30" width="4" height="4" fill="#7F77DD" opacity="0.5"/>
      <rect x="44" y="30" width="4" height="4" fill="#7F77DD" opacity="0.3"/>
      <rect x="42" y="26" width="4" height="4" fill="#7F77DD" opacity="0.3"/>
      <rect x="42" y="34" width="4" height="4" fill="#7F77DD" opacity="0.3"/>
      <rect x="82%" y="18%" width="4" height="4" fill="#92FFCB" opacity="0.45"/>
      <rect x="15%" y="84%" width="4" height="4" fill="#AFA9EC" opacity="0.35"/>
    </svg>

    <section class="os-window" aria-label="Login GuildaByte">
      <div class="os-titlebar">
        <span class="os-dot red"></span>
        <span class="os-dot yellow"></span>
        <span class="os-dot green"></span>
        <span class="os-title-text">GUILDABYTE.EXE</span>
      </div>

      <div class="os-body">
        <div class="logo-area" style="display: flex; justify-content: center; margin-bottom: 30px;">
          <a class="brand" href="{{ route('home') }}" aria-label="Voltar para inicio" style="display: inline-flex; align-items: center; gap: 10px; text-decoration: none;">
            <img class="brand-avatar" src="{{ asset('assets/HeaderTrans.png') }}" alt="" aria-hidden="true" style="width: 58px; height: 58px; border-radius: 8px; border: 1px solid var(--gb-purple-deep);">
            <span class="brand-name" style="color: var(--gb-text); font-family: 'Pixelify Sans', Inter, sans-serif; font-size: 26px; font-weight: 700; line-height: 1;">Guilda<span style="display: block; color: var(--gb-purple-3);">Byte</span></span>
          </a>
        </div>

        @if(isset($selectedPlan))
        <div class="selected-plan" style="margin-top: 0; margin-bottom: 24px; text-align: center;">
          <span style="color: var(--gb-green); font-size: 12px; font-weight: 800; text-transform: uppercase;">Plano Selecionado</span>
          <strong style="font-family: 'Pixelify Sans', Inter, sans-serif; font-size: 24px; color: var(--gb-text);">{{ $selectedPlan['title'] }}</strong>
          <span style="color: var(--gb-muted); font-size: 14px;">{{ $selectedPlan['price'] }}</span>
        </div>
        @endif

        <form method="post" action="{{ route('login') }}" data-auth-form="login">
          @csrf
          <div class="field-group">
            <label class="field-label" for="email">&#9654; EMAIL / LOGIN</label>
            <div class="input-wrapper">
              <input class="pixel-input" id="email" name="email" type="email" placeholder="seuemail@exemplo.com" autocomplete="email" required value="{{ old('email') }}">
              <span class="input-icon">@</span>
            </div>
            @error('email')
              <div style="color: var(--gb-danger); font-size: 11px; margin-top: 5px;">{{ $message }}</div>
            @enderror
          </div>

          <div class="field-group">
            <label class="field-label" for="password-login">&#9654; SENHA</label>
            <div class="input-wrapper">
              <input class="pixel-input" id="password-login" name="password" type="password" placeholder="********" autocomplete="current-password" required>
              <button class="toggle-password" type="button" data-toggle-password="#password-login" aria-label="Mostrar senha">S</button>
            </div>
            @error('password')
              <div style="color: var(--gb-danger); font-size: 11px; margin-top: 5px;">{{ $message }}</div>
            @enderror
          </div>

          <button class="px-btn" type="submit">ENTRAR NO SISTEMA</button>
        </form>

        <div class="divider-row">
          <div class="divider-line"></div>
          <span class="divider-txt">OU</span>
          <div class="divider-line"></div>
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

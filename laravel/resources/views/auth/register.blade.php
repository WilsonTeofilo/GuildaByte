<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro | GuildaByte</title>
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
      <rect x="10%" y="20%" width="4" height="4" fill="#7F77DD" opacity="0.5"/>
      <rect x="85%" y="15%" width="4" height="4" fill="#92FFCB" opacity="0.4"/>
      <rect x="75%" y="85%" width="4" height="4" fill="#AFA9EC" opacity="0.3"/>
      <rect x="15%" y="80%" width="4" height="4" fill="#7F77DD" opacity="0.3"/>
    </svg>

    <section class="os-win2" aria-label="Cadastro GuildaByte">
      <div class="os-titlebar">
        <span class="os-dot red"></span>
        <span class="os-dot yellow"></span>
        <span class="os-dot green"></span>
        <span class="os-title-text">REGISTER.EXE</span>
      </div>

      <div class="os-body2">
        <div class="auth-logo-wrapper">
          <a class="auth-brand-link" href="{{ route('home') }}">
            <img src="{{ asset('assets/HeaderTrans.webp') }}" alt="" width="60" height="60" class="auth-logo-img">
            <span class="auth-logo-text">Guilda<span class="auth-logo-sub">Byte</span></span>
          </a>
        </div>

        <div class="xp-bar-wrap">
          <div class="xp-label-row">
            <span class="xp-step-txt" id="stepTxt">STEP 1 / 2</span>
            <div class="step-dots">
              <span class="step-dot active" id="dot1"></span>
              <span class="step-dot" id="dot2"></span>
            </div>
          </div>
          <div class="xp-track">
            <div class="xp-fill auth-xp-fill" id="xpFill">
              <div class="xp-pixel-shine"></div>
            </div>
          </div>
        </div>

        <form method="post" action="{{ route('register') }}" data-auth-form="register" id="registerForm">
          @csrf

          {{-- STEP 1: Dados Pessoais --}}
          <div class="step-panel active" id="step1">
            <div class="step-header">
              <svg width="24" height="24" viewBox="0 0 24 24" class="step-icon">
                <rect x="8" y="4" width="8" height="6" fill="#AFA9EC"/>
                <rect x="6" y="10" width="12" height="4" fill="#7F77DD"/>
                <rect x="4" y="14" width="16" height="6" fill="#534AB7"/>
              </svg>
              <div>
                <div class="step-title">DADOS PESSOAIS</div>
                <div class="step-sub">Suas credenciais de acesso</div>
              </div>
            </div>

            <div class="fgrp">
              <label class="flbl" for="name">&#9654; NOME COMPLETO</label>
              <div class="input-wrap">
                <input class="pinp" id="name" name="name" type="text" placeholder="Seu nome" value="{{ old('name') }}" required minlength="2" maxlength="100">
              </div>
              @error('name')<div class="field-error-server auth-field-error">{{ $message }}</div>@enderror
            </div>

            <div class="fgrp">
              <label class="flbl" for="email">&#9654; EMAIL</label>
              <div class="input-wrap">
                <input class="pinp" id="email" name="email" type="email" placeholder="seuemail@exemplo.com" value="{{ old('email') }}" required>
              </div>
              @error('email')<div class="field-error-server auth-field-error">{{ $message }}</div>@enderror
            </div>

            <div class="fgrp">
              <label class="flbl" for="phone">&#9654; WHATSAPP</label>
              <div class="input-wrap">
                <input class="pinp" id="phone" name="phone" type="tel" placeholder="(11) 99999-9999" value="{{ old('phone') }}" maxlength="20">
              </div>
              @error('phone')<div class="field-error-server auth-field-error">{{ $message }}</div>@enderror
            </div>

            <div class="two-col">
              <div class="fgrp">
                <label class="flbl" for="pwdInput">&#9654; SENHA</label>
                <div class="input-wrap">
                  <input class="pinp" id="pwdInput" name="password" type="password" placeholder="Mín. 8 caracteres" required minlength="8" maxlength="72">
                  <button type="button" class="toggle-password" data-toggle-password="#pwdInput" aria-label="Mostrar">S</button>
                </div>
                <div class="pwd-strength" id="pwdStrengthBlocks">
                  <div class="pwd-block" id="pb1"></div>
                  <div class="pwd-block" id="pb2"></div>
                  <div class="pwd-block" id="pb3"></div>
                  <div class="pwd-block" id="pb4"></div>
                </div>
                <span id="pwdLabel" class="pwd-label"></span>
                @error('password')<div class="field-error-server auth-field-error">{{ $message }}</div>@enderror
              </div>

              <div class="fgrp">
                <label class="flbl" for="confirmPassword">&#9654; CONFIRMAR</label>
                <div class="input-wrap">
                  <input class="pinp" id="confirmPassword" name="password_confirmation" type="password" placeholder="Repita a senha" required minlength="8" maxlength="72">
                  <button type="button" class="toggle-password" data-toggle-password="#confirmPassword" aria-label="Mostrar">S</button>
                </div>
              </div>
            </div>

            <div class="btn-row">
              <button class="px-btn" type="button" data-step-next>PRÓXIMO STEP</button>
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

            <div class="bottom-link">
              <a class="px-link auth-px-link" href="{{ route('login') }}">&#9654; JÁ TENHO CONTA</a>
            </div>
          </div>

          {{-- STEP 2: Negócio & Termos --}}
          <div class="step-panel" id="step2">
            <div class="step-header">
              <svg width="24" height="24" viewBox="0 0 24 24" class="step-icon">
                <rect x="6" y="8" width="12" height="12" fill="#7F77DD"/>
                <rect x="8" y="10" width="8" height="4" fill="#92FFCB"/>
                <rect x="10" y="6" width="4" height="2" fill="#AFA9EC"/>
              </svg>
              <div>
                <div class="step-title">SEU NEGÓCIO</div>
                <div class="step-sub">Como podemos te ajudar?</div>
              </div>
            </div>

            <div class="fgrp">
              <label class="flbl" for="business_type">&#9654; TIPO DE PROJETO (OPCIONAL)</label>
              <div class="input-wrap">
                <select class="pinp" id="business_type" name="business_type">
                  <option value="">Selecione uma categoria...</option>
                  <option value="SaaS / Web App" {{ old('business_type') == 'SaaS / Web App' ? 'selected' : '' }}>SaaS / Web App</option>
                  <option value="E-commerce" {{ old('business_type') == 'E-commerce' ? 'selected' : '' }}>E-commerce</option>
                  <option value="Landing Page" {{ old('business_type') == 'Landing Page' ? 'selected' : '' }}>Landing Page / Institucional</option>
                  <option value="Sistema Interno" {{ old('business_type') == 'Sistema Interno' ? 'selected' : '' }}>Sistema Interno / ERP</option>
                  <option value="Outro" {{ old('business_type') == 'Outro' ? 'selected' : '' }}>Outro</option>
                </select>
              </div>
              @error('business_type')<div class="field-error-server auth-field-error">{{ $message }}</div>@enderror
            </div>

            <div class="fgrp">
              <label class="flbl" for="business_name">&#9654; NOME DO NEGÓCIO (OPCIONAL)</label>
              <div class="input-wrap">
                <input class="pinp" id="business_name" name="business_name" type="text" placeholder="Ex: Barbearia do Wilson" value="{{ old('business_name') }}" maxlength="100">
              </div>
              @error('business_name')<div class="field-error-server auth-field-error">{{ $message }}</div>@enderror
            </div>

            <div class="fgrp auth-fgrp-mt">
              <div class="check-row">
                <input type="checkbox" class="px-checkbox" id="terms" name="terms" required>
                <label class="check-label" for="terms">
                  Li e aceito os <a href="#">Termos de Uso</a> e a <a href="#">Política de Privacidade</a> (Obrigatório)
                </label>
              </div>
              <div class="check-row">
                <input type="checkbox" class="px-checkbox" id="marketing_email" name="marketing_email" value="1">
                <label class="check-label" for="marketing_email">Aceito receber novidades por e-mail.</label>
              </div>
              <div class="check-row">
                <input type="checkbox" class="px-checkbox" id="marketing_whatsapp" name="marketing_whatsapp" value="1">
                <label class="check-label" for="marketing_whatsapp">Aceito receber comunicações via WhatsApp.</label>
              </div>
            </div>

            <div class="btn-row">
              <button class="px-btn secondary" type="button" data-step-prev>VOLTAR</button>
              <button class="px-btn" type="submit">CRIAR CONTA</button>
            </div>
          </div>
        </form>
      </div>
    </section>
  </main>
</body>
</html>

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
  <link rel="stylesheet" href="{{ asset('style/auth.css') }}?v=1.3">
  <link rel="stylesheet" href="{{ asset('style/auth_responsive.css') }}?v=1.0">
</head>
<body>
  <main class="gb-reg">
    <div class="scanlines2"></div>
    <div class="pixel-grid2"></div>
    <div class="corner-d tl"></div>
    <div class="corner-d br"></div>
    <a class="auth-back-btn" href="{{ route('home') }}">&lt; VOLTAR AO SITE</a>

    <section class="os-win2" aria-label="Cadastro GuildaByte">
      <div class="os-tb2">
        <span class="os-d r"></span>
        <span class="os-d y"></span>
        <span class="os-d g"></span>
        <span class="os-ttxt">NOVO_AVENTUREIRO.EXE</span>
      </div>

      <div class="os-body2">
        <div class="logo-top" style="display: flex; justify-content: center; margin-bottom: 30px;">
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

        <div class="xp-bar-wrap">
          <div class="xp-label-row">
            <span class="xp-label">&#9654; EXP DE CADASTRO</span>
            <span class="xp-step-txt" id="stepTxt">STEP 1 / 2</span>
          </div>
          <div class="xp-track">
            <div class="xp-fill" id="xpFill">
              <div class="xp-pixel-shine"></div>
            </div>
          </div>
          <div class="step-dots">
            <div class="step-dot active" id="dot1"></div>
            <div class="step-dot" id="dot2"></div>
          </div>
        </div>

        <form method="post" action="{{ route('register') }}" data-auth-form="register">
          @csrf
          <input class="gb-honeypot" type="text" name="company_site" tabindex="-1" autocomplete="off" aria-hidden="true">

          <div class="step-panel active" id="step1">
            <div class="step-header">
              <svg class="step-icon" width="40" height="44" viewBox="0 0 40 44" aria-hidden="true">
                <rect x="12" y="0" width="4" height="4" fill="#AFA9EC"/><rect x="16" y="0" width="8" height="4" fill="#7F77DD"/><rect x="24" y="0" width="4" height="4" fill="#AFA9EC"/>
                <rect x="8" y="4" width="24" height="4" fill="#534AB7"/><rect x="12" y="8" width="16" height="8" fill="#f6f4ff"/>
                <rect x="12" y="12" width="4" height="4" fill="#26215c"/><rect x="24" y="12" width="4" height="4" fill="#26215c"/><rect x="16" y="16" width="8" height="4" fill="#26215c"/>
                <rect x="8" y="20" width="24" height="4" fill="#7F77DD"/><rect x="4" y="24" width="32" height="8" fill="#534AB7"/><rect x="4" y="32" width="8" height="12" fill="#26215c"/><rect x="28" y="32" width="8" height="12" fill="#26215c"/>
              </svg>
              <div>
                <div class="step-title">DADOS PESSOAIS</div>
                <div class="step-sub">Suas informacoes de acesso</div>
              </div>
            </div>

            <div class="fgrp">
              <label class="flbl" for="name">&#9654; NOME COMPLETO</label>
              <input class="pinp" id="name" name="name" type="text" placeholder="Seu nome completo" autocomplete="name" required value="{{ old('name') }}">
            </div>

            <div class="fgrp">
              <label class="flbl" for="email">&#9654; EMAIL</label>
              <input class="pinp" id="email" name="email" type="email" placeholder="seuemail@exemplo.com" autocomplete="email" required value="{{ old('email') }}">
              @error('email')
                <div style="color: var(--gb-danger); font-size: 11px; margin-top: 5px;">{{ $message }}</div>
              @enderror
            </div>

            <div class="fgrp">
              <label class="flbl" for="whatsInput">&#9654; WHATSAPP</label>
              <input class="pinp" id="whatsInput" name="phone" type="tel" placeholder="(11) 99999-9999" autocomplete="tel" value="{{ old('phone') }}">
            </div>

            <div class="two-col">
              <div class="fgrp">
                <label class="flbl" for="pwdInput">&#9654; SENHA</label>
                <input class="pinp" id="pwdInput" name="password" type="password" placeholder="********" autocomplete="new-password" required>
                <div class="pwd-strength">
                  <div class="pwd-block" id="pb1"></div>
                  <div class="pwd-block" id="pb2"></div>
                  <div class="pwd-block" id="pb3"></div>
                  <div class="pwd-block" id="pb4"></div>
                </div>
                @error('password')
                  <div style="color: var(--gb-danger); font-size: 11px; margin-top: 5px;">{{ $message }}</div>
                @enderror
              </div>
              <div class="fgrp">
                <label class="flbl" for="confirmPassword">&#9654; CONFIRMAR</label>
                <input class="pinp" id="confirmPassword" name="password_confirmation" type="password" placeholder="********" autocomplete="new-password" required>
              </div>
            </div>

            <div class="btn-row">
              <button class="px-btn2" type="button" data-step-next>PROXIMO</button>
            </div>
            <div class="bottom-link">
              <a class="px-lnk" href="{{ route('login') }}">&#9654; JA TENHO CONTA</a>
            </div>
          </div>

          <div class="step-panel" id="step2">
            <div class="step-header">
              <svg class="step-icon" width="40" height="44" viewBox="0 0 40 44" aria-hidden="true">
                <rect x="8" y="0" width="24" height="4" fill="#7F77DD"/><rect x="4" y="4" width="32" height="4" fill="#534AB7"/><rect x="4" y="8" width="4" height="28" fill="#26215c"/><rect x="32" y="8" width="4" height="28" fill="#26215c"/>
                <rect x="8" y="8" width="24" height="28" fill="#101018"/><rect x="12" y="12" width="16" height="4" fill="#AFA9EC"/><rect x="12" y="20" width="12" height="4" fill="#7F77DD"/><rect x="12" y="28" width="8" height="4" fill="#534AB7"/>
                <rect x="36" y="12" width="4" height="4" fill="#92FFCB"/>
              </svg>
              <div>
                <div class="step-title">SEU NEGOCIO</div>
                <div class="step-sub">Nos conte sobre voce</div>
              </div>
            </div>

            <div class="fgrp">
              <label class="flbl" for="business">&#9654; TIPO DE NEGOCIO</label>
              <select class="pinp" id="business" name="business_type">
                <option value="">Selecione seu segmento...</option>
                <option>Barbearia</option>
                <option>Fotografia</option>
                <option>Tatuagem</option>
                <option>Loja / E-commerce</option>
                <option>Estetica / Beleza</option>
                <option>Moda / Vestuario</option>
                <option>Gastronomia</option>
                <option>Outro</option>
              </select>
            </div>

            <div class="fgrp">
              <label class="flbl" for="businessName">&#9654; NOME DO NEGOCIO</label>
              <input class="pinp" id="businessName" name="business_name" type="text" placeholder="Ex: Barbearia do Wilson" value="{{ old('business_name') }}">
            </div>

            <div class="fgrp">
              <label class="flbl" for="projectDescription">&#9654; DESCREVA SEU PROJETO</label>
              <textarea class="pinp" id="projectDescription" name="project_description" placeholder="Conta o que voce precisa... Agendamento? Loja? Cardapio? Qualquer coisa!"></textarea>
            </div>

            <div class="fgrp">
              <div class="check-row">
                <input type="checkbox" class="px-checkbox" id="terms" name="terms" value="1" required>
                <label class="check-label" for="terms">
                  Li e aceito os <a href="#">Termos de Uso</a> e a <a href="#">Politica de Privacidade</a> da GuildaByte
                </label>
              </div>
            </div>
            <div class="fgrp">
              <div class="check-row">
                <input type="checkbox" class="px-checkbox" id="marketingEmail" name="marketing_email" value="1">
                <label class="check-label" for="marketingEmail">Aceito receber ofertas por email.</label>
              </div>
              <div class="check-row">
                <input type="checkbox" class="px-checkbox" id="marketingWhatsapp" name="marketing_whatsapp" value="1">
                <label class="check-label" for="marketingWhatsapp">Aceito receber ofertas por WhatsApp.</label>
              </div>
            </div>

            <div class="btn-row">
              <button class="px-btn2 secondary" type="button" data-step-prev>VOLTAR</button>
              <button class="px-btn2" type="submit">CRIAR CONTA</button>
            </div>
          </div>

          <div class="success-panel" id="successPanel">
            <svg class="success-icon" width="64" height="64" viewBox="0 0 64 64" aria-hidden="true">
              <rect x="24" y="0" width="16" height="8" fill="#92FFCB"/><rect x="16" y="8" width="32" height="8" fill="#92FFCB"/><rect x="8" y="16" width="48" height="24" fill="#92FFCB"/><rect x="16" y="40" width="32" height="8" fill="#92FFCB"/><rect x="24" y="48" width="16" height="8" fill="#92FFCB"/>
              <rect x="20" y="24" width="8" height="8" fill="#050507"/><rect x="36" y="24" width="8" height="8" fill="#050507"/><rect x="24" y="38" width="16" height="4" fill="#050507"/>
            </svg>
            <div class="step-title success-title">CONTA CRIADA!</div>
            <p class="check-label success-copy">Bem-vindo a guilda. Redirecionando para o painel...</p>
            <a class="px-btn2 success-action" href="{{ route('login') }}">IR PARA LOGIN</a>
          </div>

          <p class="auth-status" data-status></p>
        </form>
      </div>
    </section>
  </main>
</body>
</html>

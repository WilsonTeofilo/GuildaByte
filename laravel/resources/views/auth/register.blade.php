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
  <link rel="stylesheet" href="{{ asset('style/auth.css') }}?v=1.4">
  <link rel="stylesheet" href="{{ asset('style/auth_responsive.css') }}?v=1.0">

  <style>
    /* === Força de Senha === */
    .pwd-strength { display: flex; gap: 4px; margin-top: 8px; }
    .pwd-block { flex: 1; height: 4px; background: #26215c; border-radius: 2px; transition: background 0.3s; }
    .pwd-block.weak   { background: #ff4d4d; }
    .pwd-block.medium { background: #ffaa00; }
    .pwd-block.strong { background: #92FFCB; }

    .pwd-label {
      font-size: 10px;
      margin-top: 4px;
      font-family: 'Press Start 2P', monospace;
      display: block;
    }
    .pwd-label.weak   { color: #ff4d4d; }
    .pwd-label.medium { color: #ffaa00; }
    .pwd-label.strong { color: #92FFCB; }

    /* === Match de Confirmação === */
    .confirm-msg {
      font-size: 10px;
      margin-top: 4px;
      font-family: monospace;
      display: none;
    }
    .confirm-msg.match    { color: #92FFCB; display: block; }
    .confirm-msg.no-match { color: #ff4d4d; display: block; }

    /* === Google desabilitado === */
    .google-btn-disabled {
      background: var(--gb-surface-2);
      color: #555;
      border: 1px dashed #333;
      padding: 12px;
      border-radius: 6px;
      font-family: 'Press Start 2P', monospace;
      font-size: 8px;
      text-align: center;
      margin-bottom: 16px;
      cursor: not-allowed;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      opacity: 0.5;
    }
  </style>
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
            <img class="brand-avatar" src="{{ asset('assets/HeaderTrans.webp') }}" alt="Logo GuildaByte" width="58" height="58" loading="lazy" style="width: 58px; height: 58px; border-radius: 8px; border: 1px solid var(--gb-purple-deep);">
            <span class="brand-name" style="color: var(--gb-text); font-family: 'Pixelify Sans', Inter, sans-serif; font-size: 26px; font-weight: 700; line-height: 1;">Guilda<span style="display: block; color: var(--gb-purple-3);">Byte</span></span>
          </a>
        </div>

        @if(isset($selectedPlan) && $selectedPlan)
        <div class="selected-plan" style="margin-top: 0; margin-bottom: 24px; text-align: center; padding: 12px; border: 1px solid var(--gb-green); border-radius: 8px; background: rgba(146,255,203,0.06);">
          <span style="color: var(--gb-green); font-size: 11px; font-weight: 800; text-transform: uppercase; display: block; margin-bottom: 4px;">&#9654; Plano Selecionado</span>
          <strong style="font-family: 'Pixelify Sans', Inter, sans-serif; font-size: 22px; color: var(--gb-text); display: block;">{{ $selectedPlan['title'] }}</strong>
          <span style="color: var(--gb-green); font-size: 18px; font-weight: 800;">{{ $selectedPlan['price'] }}</span>
          @if(!empty($selectedPlan['note']))
            <small style="color: var(--gb-muted); font-size: 11px; display: block; margin-top: 2px;">{{ $selectedPlan['note'] }}</small>
          @endif
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

        <form method="post" action="{{ route('register') }}" data-auth-form="register" id="registerForm" novalidate>
          @csrf
          {{-- Honeypot anti-bot --}}
          <input class="gb-honeypot" type="text" name="company_site" tabindex="-1" autocomplete="off" aria-hidden="true" style="display:none;">

          {{-- =================== STEP 1 =================== --}}
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

            {{-- NOME --}}
            <div class="fgrp">
              <label class="flbl" for="name">&#9654; NOME COMPLETO</label>
              <input class="pinp" id="name" name="name"
                type="text"
                placeholder="Seu nome completo"
                autocomplete="name"
                required
                minlength="2"
                maxlength="100"
                value="{{ old('name') }}">
              <span class="field-hint" id="name-hint" style="font-size:10px; color: var(--gb-muted);"></span>
              @error('name')
                <span style="color: var(--gb-danger); font-size: 11px; margin-top: 5px; display: block;">{{ $message }}</span>
              @enderror
            </div>

            {{-- EMAIL --}}
            <div class="fgrp">
              <label class="flbl" for="email">&#9654; EMAIL</label>
              <input class="pinp" id="email" name="email"
                type="email"
                placeholder="seuemail@exemplo.com"
                autocomplete="email"
                required
                maxlength="255"
                value="{{ old('email') }}">
              @error('email')
                <div style="color: var(--gb-danger); font-size: 11px; margin-top: 5px;">{{ $message }}</div>
              @enderror
            </div>

            {{-- WHATSAPP --}}
            <div class="fgrp">
              <label class="flbl" for="whatsInput">&#9654; WHATSAPP</label>
              <input class="pinp" id="whatsInput" name="phone"
                type="tel"
                placeholder="(11) 99999-9999"
                autocomplete="tel"
                maxlength="20"
                pattern="[\d\s\(\)\+\-]+"
                value="{{ old('phone') }}">
              @error('phone')
                <div style="color: var(--gb-danger); font-size: 11px; margin-top: 5px;">{{ $message }}</div>
              @enderror
            </div>

            {{-- SENHAS --}}
            <div class="two-col">
              <div class="fgrp">
                <label class="flbl" for="pwdInput">&#9654; SENHA</label>
                <input class="pinp" id="pwdInput" name="password"
                  type="password"
                  placeholder="Mín. 8 caracteres"
                  autocomplete="new-password"
                  required
                  minlength="8"
                  maxlength="72">
                <div class="pwd-strength">
                  <div class="pwd-block" id="pb1"></div>
                  <div class="pwd-block" id="pb2"></div>
                  <div class="pwd-block" id="pb3"></div>
                  <div class="pwd-block" id="pb4"></div>
                </div>
                <span class="pwd-label" id="pwdLabel"></span>
                @error('password')
                  <div style="color: var(--gb-danger); font-size: 11px; margin-top: 5px;">{{ $message }}</div>
                @enderror
              </div>

              <div class="fgrp">
                <label class="flbl" for="confirmPassword">&#9654; CONFIRMAR</label>
                <input class="pinp" id="confirmPassword" name="password_confirmation"
                  type="password"
                  placeholder="Repita a senha"
                  autocomplete="new-password"
                  required
                  minlength="8"
                  maxlength="72">
                <span class="confirm-msg" id="confirmMsg"></span>
              </div>
            </div>

            {{-- Botão próximo (validação JS antes de avançar) --}}
            <div class="btn-row" style="margin-top: 20px;">
              <button class="px-btn2" type="button" id="btnStep1Next">PROXIMO</button>
            </div>

            {{-- Divisor OU --}}
            <div class="divider-row" style="margin: 20px 0; display: flex; align-items: center; gap: 10px;">
              <div style="flex: 1; height: 1px; background: var(--gb-border);"></div>
              <span style="font-family: 'Press Start 2P', monospace; font-size: 8px; color: var(--gb-muted);">OU</span>
              <div style="flex: 1; height: 1px; background: var(--gb-border);"></div>
            </div>

            {{-- Google Auth — em breve (sem rota ainda → sem 404) --}}
            <div class="google-btn-disabled" title="Google Auth em breve">
              <svg width="16" height="16" viewBox="0 0 48 48">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
              </svg>
              GOOGLE AUTH — EM BREVE
            </div>

            <div class="bottom-link">
              <a class="px-lnk" href="{{ route('login') }}">&#9654; JA TENHO CONTA</a>
            </div>
          </div>

          {{-- =================== STEP 2 =================== --}}
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

            {{-- Tipo de negócio --}}
            <div class="fgrp">
              <label class="flbl" for="business">&#9654; TIPO DE NEGOCIO</label>
              <select class="pinp" id="business" name="business_type">
                <option value="">Selecione seu segmento...</option>
                <option {{ old('business_type') == 'Barbearia' ? 'selected' : '' }}>Barbearia</option>
                <option {{ old('business_type') == 'Fotografia' ? 'selected' : '' }}>Fotografia</option>
                <option {{ old('business_type') == 'Tatuagem' ? 'selected' : '' }}>Tatuagem</option>
                <option {{ old('business_type') == 'Loja / E-commerce' ? 'selected' : '' }}>Loja / E-commerce</option>
                <option {{ old('business_type') == 'Estetica / Beleza' ? 'selected' : '' }}>Estetica / Beleza</option>
                <option {{ old('business_type') == 'Moda / Vestuario' ? 'selected' : '' }}>Moda / Vestuario</option>
                <option {{ old('business_type') == 'Gastronomia' ? 'selected' : '' }}>Gastronomia</option>
                <option {{ old('business_type') == 'Outro' ? 'selected' : '' }}>Outro</option>
              </select>
            </div>

            {{-- Nome do negócio --}}
            <div class="fgrp">
              <label class="flbl" for="businessName">&#9654; NOME DO NEGOCIO</label>
              <input class="pinp" id="businessName" name="business_name"
                type="text"
                placeholder="Ex: Barbearia do Wilson"
                maxlength="100"
                value="{{ old('business_name') }}">
            </div>

            {{-- Descrição --}}
            <div class="fgrp">
              <label class="flbl" for="projectDescription">&#9654; DESCREVA SEU PROJETO</label>
              <textarea class="pinp" id="projectDescription" name="project_description"
                placeholder="Conta o que voce precisa... Agendamento? Loja? Cardapio?"
                rows="3"
                maxlength="1000">{{ old('project_description') }}</textarea>
              <span style="font-size: 10px; color: var(--gb-muted); text-align: right; display: block;" id="descCounter">0/1000</span>
            </div>

            {{-- Checkboxes LGPD --}}
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

            <div id="step2Error" style="color: var(--gb-danger); font-size: 11px; margin-bottom: 10px; display: none;"></div>

            <div class="btn-row">
              <button class="px-btn2 secondary" type="button" data-step-prev>VOLTAR</button>
              <button class="px-btn2" type="submit" id="btnSubmit">CRIAR CONTA</button>
            </div>
          </div>

          {{-- Painel de sucesso --}}
          <div class="success-panel" id="successPanel" style="display:none;">
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

  <script>
  (function () {
    // ── Força de senha ──────────────────────────────────────────
    const pwdInput = document.getElementById('pwdInput');
    const confirmInput = document.getElementById('confirmPassword');
    const blocks = [document.getElementById('pb1'), document.getElementById('pb2'), document.getElementById('pb3'), document.getElementById('pb4')];
    const pwdLabel = document.getElementById('pwdLabel');
    const confirmMsg = document.getElementById('confirmMsg');

    function scorePassword(p) {
      let score = 0;
      if (p.length >= 8)  score++;
      if (p.length >= 12) score++;
      if (/[A-Z]/.test(p) && /[a-z]/.test(p)) score++;
      if (/[0-9]/.test(p)) score++;
      if (/[^A-Za-z0-9]/.test(p)) score++;
      return Math.min(score, 4);
    }

    function updateStrength() {
      const val = pwdInput.value;
      const score = val.length === 0 ? 0 : scorePassword(val);
      const levels = ['', 'weak', 'medium', 'strong', 'strong'];
      const labels = ['', 'FRACA', 'MÉDIA', 'FORTE', 'MUITO FORTE'];

      blocks.forEach((b, i) => {
        b.className = 'pwd-block';
        if (i < score) b.classList.add(levels[score]);
      });

      pwdLabel.className = 'pwd-label';
      if (val.length > 0) {
        pwdLabel.classList.add(levels[score]);
        pwdLabel.textContent = '[ FORÇA: ' + labels[score] + ' ]';
      } else {
        pwdLabel.textContent = '';
      }

      checkMatch();
    }

    function checkMatch() {
      const p1 = pwdInput.value;
      const p2 = confirmInput.value;
      confirmMsg.className = 'confirm-msg';
      if (p2.length === 0) return;
      if (p1 === p2) {
        confirmMsg.classList.add('match');
        confirmMsg.textContent = '✓ SENHAS IGUAIS';
      } else {
        confirmMsg.classList.add('no-match');
        confirmMsg.textContent = '✗ SENHAS DIVERGENTES';
      }
    }

    pwdInput.addEventListener('input', updateStrength);
    confirmInput.addEventListener('input', checkMatch);

    // ── Contador textarea ───────────────────────────────────────
    const desc = document.getElementById('projectDescription');
    const counter = document.getElementById('descCounter');
    if (desc && counter) {
      desc.addEventListener('input', () => counter.textContent = desc.value.length + '/1000');
    }

    // ── Botão Próximo (Step 1 → validação antes de avançar) ─────
    document.getElementById('btnStep1Next').addEventListener('click', function () {
      const name  = document.getElementById('name');
      const email = document.getElementById('email');
      const pwd   = pwdInput;
      const conf  = confirmInput;
      let errors  = [];

      if (name.value.trim().length < 2)  errors.push('Nome inválido (mín. 2 caracteres).');
      if (!email.value.includes('@'))    errors.push('E-mail inválido.');
      if (pwd.value.length < 8)         errors.push('Senha deve ter no mínimo 8 caracteres.');
      if (pwd.value !== conf.value)      errors.push('As senhas não conferem.');

      if (errors.length > 0) {
        alert('⚠️ ' + errors.join('\n'));
        return;
      }

      // Avança para Step 2
      document.getElementById('step1').classList.remove('active');
      document.getElementById('step2').classList.add('active');
      document.getElementById('stepTxt').textContent = 'STEP 2 / 2';
      document.getElementById('dot2').classList.add('active');
      const fill = document.getElementById('xpFill');
      if (fill) fill.style.width = '100%';
    });

    // ── Botão Voltar ────────────────────────────────────────────
    document.querySelector('[data-step-prev]').addEventListener('click', function () {
      document.getElementById('step2').classList.remove('active');
      document.getElementById('step1').classList.add('active');
      document.getElementById('stepTxt').textContent = 'STEP 1 / 2';
      document.getElementById('dot2').classList.remove('active');
      const fill = document.getElementById('xpFill');
      if (fill) fill.style.width = '50%';
    });

    // ── Bloquear submit se termos não aceitos ───────────────────
    document.getElementById('registerForm').addEventListener('submit', function (e) {
      const terms = document.getElementById('terms');
      if (!terms.checked) {
        e.preventDefault();
        document.getElementById('step2Error').textContent = '⚠️ Você precisa aceitar os Termos de Uso para continuar.';
        document.getElementById('step2Error').style.display = 'block';
        return;
      }
      document.getElementById('step2Error').style.display = 'none';
    });

  })();
  </script>
</body>
</html>

/**
 * auth.js — Login + Cadastro
 * Regra: front-end valida para UX. Backend SEMPRE revalida. Nunca confiar no front.
 *
 * Estrutura:
 *   1. Utilitários (rodam em qualquer tela de auth)
 *   2. Bloco Login  — OTP toggle
 *   3. Bloco Cadastro — multi-step + força de senha
 */
document.addEventListener('DOMContentLoaded', () => {

    // ════════════════════════════════════════════════════════════════
    // 1. UTILITÁRIOS — rodam em qualquer tela
    // ════════════════════════════════════════════════════════════════

    // Toggle mostrar/ocultar senha
    document.querySelectorAll('[data-toggle-password]').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.querySelector(btn.dataset.togglePassword);
            if (!input) return;
            const show   = input.type === 'password';
            input.type   = show ? 'text' : 'password';
            btn.textContent  = show ? 'O' : 'S';
            btn.setAttribute('aria-label', show ? 'Ocultar senha' : 'Mostrar senha');
        });
    });

    // Helpers de erro inline
    function showError(input, message) {
        if (!input) return;
        clearError(input);
        const err = document.createElement('span');
        err.className   = 'field-error';
        err.textContent = message;
        err.setAttribute('role', 'alert');
        err.style.cssText = 'color:#ff7893;font-size:11px;display:block;margin-top:4px;font-weight:600;';
        input.after(err);
        input.style.borderColor = '#ff7893';
    }

    function clearError(input) {
        if (!input) return;
        input.style.borderColor = '';
        const next = input.nextElementSibling;
        if (next?.classList.contains('field-error')) next.remove();
    }

    function clearAllErrors() {
        document.querySelectorAll('.field-error').forEach(e => e.remove());
        document.querySelectorAll('.pinp, .pixel-input').forEach(i => i.style.borderColor = '');
    }

    // ════════════════════════════════════════════════════════════════
    // 2. BLOCO LOGIN — OTP/Token toggle
    //    Condição: o formulário OTP existe (só na tela de login)
    // ════════════════════════════════════════════════════════════════

    const formOtp     = document.getElementById('formLoginOtp');
    const formDefault = document.getElementById('formLoginDefault');
    const btnToggle   = document.getElementById('toggleAuthMode');

    if (formOtp && formDefault && btnToggle) {
        let isOtpMode = false;

        btnToggle.addEventListener('click', () => {
            isOtpMode = !isOtpMode;
            formDefault.style.display = isOtpMode ? 'none' : 'block';
            formOtp.style.display     = isOtpMode ? 'block' : 'none';

            if (isOtpMode) {
                btnToggle.textContent = '[ Voltar para login com senha ]';
                btnToggle.style.borderColor = 'var(--gb-green)';
                btnToggle.style.color       = 'var(--gb-green)';
            } else {
                btnToggle.textContent = '[ Login sem senha (token) ]';
                btnToggle.style.borderColor = 'var(--gb-purple)';
                btnToggle.style.color       = 'var(--gb-purple-light)';
            }
        });

        // OTP — enviar código
        const btnSend   = document.getElementById('btnSendOtp');
        const btnVerify = document.getElementById('btnVerifyOtp');
        const emailInp  = document.getElementById('otpEmail');
        const codeInp   = document.getElementById('otpCode');
        const step1     = document.getElementById('otpStep1');
        const step2     = document.getElementById('otpStep2');
        const otpError  = document.getElementById('otpError');
        const sendUrl   = formOtp.dataset.sendUrl;
        const verifyUrl = formOtp.dataset.verifyUrl;
        const csrfToken = () => document.querySelector('input[name="_token"]')?.value || '';

        btnSend?.addEventListener('click', async () => {
            const email = emailInp?.value?.trim();
            if (!email) { showError(emailInp, 'Digite seu e-mail.'); return; }

            btnSend.textContent = 'ENVIANDO...';
            btnSend.disabled    = true;

            try {
                const res  = await fetch(sendUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
                    body: JSON.stringify({ email }),
                });
                const data = await res.json();

                if (data.success) {
                    step1.style.display = 'none';
                    step2.style.display = 'block';
                } else {
                    showError(emailInp, data.message || 'Erro ao enviar código.');
                    btnSend.textContent = 'ENVIAR CÓDIGO';
                    btnSend.disabled    = false;
                }
            } catch {
                showError(emailInp, 'Erro de conexão. Tente novamente.');
                btnSend.textContent = 'ENVIAR CÓDIGO';
                btnSend.disabled    = false;
            }
        });

        // OTP — verificar código
        btnVerify?.addEventListener('click', async () => {
            const email = emailInp?.value?.trim();
            const code  = codeInp?.value?.trim();
            if (code?.length !== 6) { otpError.textContent = 'Código deve ter 6 dígitos.'; otpError.style.display = 'block'; return; }

            btnVerify.textContent = 'VALIDANDO...';
            btnVerify.disabled    = true;
            otpError.style.display = 'none';

            try {
                const res  = await fetch(verifyUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
                    body: JSON.stringify({ email, code }),
                });
                const data = await res.json();

                if (data.success) {
                    btnVerify.textContent = 'SUCESSO! REDIRECIONANDO...';
                    window.location.href  = data.redirect;
                } else {
                    otpError.textContent = data.message || 'Código inválido ou expirado.';
                    otpError.style.display = 'block';
                    btnVerify.textContent  = 'CONFIRMAR CÓDIGO';
                    btnVerify.disabled     = false;
                }
            } catch {
                otpError.textContent = 'Erro de conexão.';
                otpError.style.display = 'block';
                btnVerify.textContent  = 'CONFIRMAR CÓDIGO';
                btnVerify.disabled     = false;
            }
        });
    }

    // ════════════════════════════════════════════════════════════════
    // 3. BLOCO CADASTRO — multi-step + força de senha + máscara tel.
    //    Condição: .step-panel existe (só na tela de cadastro)
    // ════════════════════════════════════════════════════════════════

    const steps = document.querySelectorAll('.step-panel');
    if (!steps.length) return;

    let current = 0;
    const xpFill  = document.getElementById('xpFill');
    const stepTxt = document.getElementById('stepTxt');
    const dot1    = document.getElementById('dot1');
    const dot2    = document.getElementById('dot2');

    function goTo(index) {
        steps[current].classList.remove('active');
        current = index;
        steps[current].classList.add('active');
        clearAllErrors();
        if (xpFill)  xpFill.style.width = current === 0 ? '50%' : '100%';
        if (stepTxt) stepTxt.textContent = `STEP ${current + 1} / ${steps.length}`;
        if (dot1)    dot1.classList.toggle('active', current === 0);
        if (dot2)    dot2.classList.toggle('active', current === 1);
    }

    function validateStep1() {
        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const pwd  = document.getElementById('pwdInput');
        const conf = document.getElementById('confirmPassword');
        let valid  = true;

        if (!name?.value.trim() || name.value.trim().length < 2) {
            showError(name, 'Nome obrigatório (mín. 2 caracteres).'); valid = false;
        }
        const emailRx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email?.value.trim() || !emailRx.test(email.value)) {
            showError(email, 'E-mail inválido.'); valid = false;
        }
        if (!pwd?.value || pwd.value.length < 8) {
            showError(pwd, 'Senha: mínimo 8 caracteres.'); valid = false;
        }
        if (pwd?.value && conf?.value && pwd.value !== conf.value) {
            showError(conf, 'As senhas não conferem.'); valid = false;
        }
        if (!conf?.value) {
            showError(conf, 'Confirme sua senha.'); valid = false;
        }
        return valid;
    }

    document.querySelectorAll('[data-step-next]').forEach(btn =>
        btn.addEventListener('click', () => {
            if (current < steps.length - 1 && validateStep1()) goTo(current + 1);
        })
    );

    document.querySelectorAll('[data-step-prev]').forEach(btn =>
        btn.addEventListener('click', () => { if (current > 0) goTo(current - 1); })
    );

    // Proteção dupla no submit
    document.querySelector('[data-auth-form="register"]')?.addEventListener('submit', e => {
        const pwd  = document.getElementById('pwdInput');
        const conf = document.getElementById('confirmPassword');
        if (pwd?.value !== conf?.value) {
            e.preventDefault();
            showError(conf, 'As senhas não conferem.');
            conf?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    // Indicador de força da senha
    const pwdInput  = document.getElementById('pwdInput');
    const confInput = document.getElementById('confirmPassword');
    const blocks    = ['pb1','pb2','pb3','pb4'].map(id => document.getElementById(id)).filter(Boolean);
    const pwdLabel  = document.getElementById('pwdLabel');

    if (pwdInput && blocks.length) {
        pwdInput.addEventListener('input', () => {
            const v     = pwdInput.value;
            const score = [
                v.length >= 8,
                /[A-Z]/.test(v) && /[a-z]/.test(v),
                /[0-9]/.test(v),
                /[^A-Za-z0-9]/.test(v),
            ].filter(Boolean).length;

            const colors = ['', '#ff7893', '#f4a261', '#e9c46a', '#92FFCB'];
            const labels = ['', '[ FRACA ]', '[ MÉDIA ]', '[ FORTE ]', '[ MUITO FORTE ]'];
            blocks.forEach((b, i) => { b.style.background = i < score ? colors[score] : ''; });
            if (pwdLabel) {
                pwdLabel.textContent = v.length > 0 ? (labels[score] || labels[1]) : '';
                pwdLabel.style.color = colors[score] || '#ff7893';
            }
            if (confInput?.value && pwdInput.value === confInput.value) clearError(confInput);
        });

        confInput?.addEventListener('input', () => {
            if (confInput.value && pwdInput.value !== confInput.value) {
                showError(confInput, 'Senhas não conferem.');
            } else {
                clearError(confInput);
            }
        });
    }

    // Máscara de telefone
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', e => {
            let val = e.target.value.replace(/\D/g, '');
            if (val.length > 0) val = '(' + val;
            if (val.length > 3) val = val.slice(0, 3) + ') ' + val.slice(3);
            if (val.length > 10) val = val.slice(0, 10) + '-' + val.slice(10, 14);
            e.target.value = val;
        });
    }

});

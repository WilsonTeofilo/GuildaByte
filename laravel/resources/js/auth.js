/**
 * auth.js — Interações das telas de Login e Cadastro
 * Responsável por: multi-step, validação client-side, força da senha, toggle de senha.
 *
 * REGRA: front-end valida para UX. Backend SEMPRE revalida. Nunca confiar no front.
 */
document.addEventListener('DOMContentLoaded', () => {

    // ── Toggle mostrar/ocultar senha ──────────────────────────────
    // NOTA: deve rodar em QUALQUER tela de auth — não pode estar dentro do bloco de steps
    document.querySelectorAll('[data-toggle-password]').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.querySelector(btn.dataset.togglePassword);
            if (!input) return;
            const isHidden  = input.type === 'password';
            input.type      = isHidden ? 'text' : 'password';
            btn.textContent = isHidden ? 'O' : 'S';
            btn.setAttribute('aria-label', isHidden ? 'Ocultar senha' : 'Mostrar senha');
        });
    });

    // ── Helpers de erro inline ────────────────────────────────────
    function showError(input, message) {
        if (!input) return;
        clearError(input);
        const err       = document.createElement('span');
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

    // ── Tela de Cadastro: Multi-step ──────────────────────────────
    const steps = document.querySelectorAll('.step-panel');
    if (!steps.length) return; // só roda no register — toggle de senha já foi registrado acima

    const xpFill  = document.getElementById('xpFill');
    const stepTxt = document.getElementById('stepTxt');
    const dot1    = document.getElementById('dot1');
    const dot2    = document.getElementById('dot2');

    let current = 0;

    function goTo(index) {
        steps[current].classList.remove('active');
        current = index;
        steps[current].classList.add('active');
        clearAllErrors();

        if (xpFill)  xpFill.style.width  = current === 0 ? '50%' : '100%';
        if (stepTxt) stepTxt.textContent  = `STEP ${current + 1} / ${steps.length}`;
        if (dot1)    dot1.classList.toggle('active', current === 0);
        if (dot2)    dot2.classList.toggle('active', current === 1);
    }

    /** Valida Step 1 — APENAS para UX. Backend revalida tudo. */
    function validateStep1() {
        const name  = document.getElementById('name');
        const email = document.getElementById('email');
        const pwd   = document.getElementById('pwdInput');
        const conf  = document.getElementById('confirmPassword');
        let valid   = true;

        if (!name?.value.trim() || name.value.trim().length < 2) {
            showError(name, 'Nome obrigatório (mín. 2 caracteres).');
            valid = false;
        }

        const emailRx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email?.value.trim() || !emailRx.test(email.value)) {
            showError(email, 'E-mail inválido.');
            valid = false;
        }

        if (!pwd?.value || pwd.value.length < 8) {
            showError(pwd, 'Senha: mínimo 8 caracteres.');
            valid = false;
        }

        if (pwd?.value && conf?.value && pwd.value !== conf.value) {
            showError(conf, 'As senhas não conferem.');
            valid = false;
        }

        if (!conf?.value) {
            showError(conf, 'Confirme sua senha.');
            valid = false;
        }

        return valid;
    }

    document.querySelectorAll('[data-step-next]').forEach(btn => {
        btn.addEventListener('click', () => {
            if (current < steps.length - 1 && validateStep1()) goTo(current + 1);
        });
    });

    document.querySelectorAll('[data-step-prev]').forEach(btn => {
        btn.addEventListener('click', () => {
            if (current > 0) goTo(current - 1);
        });
    });

    // Dupla proteção no submit (server vai revalidar de qualquer forma)
    document.querySelector('[data-auth-form="register"]')?.addEventListener('submit', e => {
        const pwd  = document.getElementById('pwdInput');
        const conf = document.getElementById('confirmPassword');
        if (pwd?.value !== conf?.value) {
            e.preventDefault();
            showError(conf, 'As senhas não conferem.');
            conf?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    // ── Password strength indicator ───────────────────────────────
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

            blocks.forEach((b, i) => {
                b.style.background = i < score ? colors[score] : '';
            });

            if (pwdLabel) {
                pwdLabel.textContent = v.length > 0 ? labels[score] || labels[1] : '';
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

    // ── Input Mask (WhatsApp) ─────────────────────────────────────
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', (e) => {
            let val = e.target.value.replace(/\D/g, ''); // remove tudo que não for dígito
            
            // Máscara (11) 99999-9999
            if (val.length > 0) {
                val = '(' + val;
            }
            if (val.length > 3) {
                val = val.slice(0, 3) + ') ' + val.slice(3);
            }
            if (val.length > 10) {
                val = val.slice(0, 10) + '-' + val.slice(10, 14);
            }
            
            e.target.value = val;
        });
    }

    // ── Lógica OTP (Login Screen) ─────────────────────────────────
    const formOtp = document.getElementById('formLoginOtp');
    if (formOtp) {
        const btnToggle   = document.getElementById('toggleAuthMode');
        const formDefault = document.getElementById('formLoginDefault');
        let isOtpMode     = false;
        
        btnToggle?.addEventListener('click', () => {
            isOtpMode = !isOtpMode;
            if (isOtpMode) {
                formDefault.style.display = 'none';
                formOtp.style.display = 'block';
                btnToggle.textContent = '[ ALTERNAR PARA LOGIN COM SENHA ]';
                btnToggle.style.borderColor = 'var(--gb-green)';
                btnToggle.style.color = 'var(--gb-green)';
            } else {
                formDefault.style.display = 'block';
                formOtp.style.display = 'none';
                btnToggle.textContent = '[ ALTERNAR PARA LOGIN SEM SENHA (OTP) ]';
                btnToggle.style.borderColor = 'var(--gb-purple)';
                btnToggle.style.color = 'var(--gb-purple-light)';
            }
        });

        const btnSend   = document.getElementById('btnSendOtp');
        const btnVerify = document.getElementById('btnVerifyOtp');
        const emailInp  = document.getElementById('otpEmail');
        const codeInp   = document.getElementById('otpCode');
        const step1     = document.getElementById('otpStep1');
        const step2     = document.getElementById('otpStep2');
        const otpError  = document.getElementById('otpError');
        const sendUrl   = formOtp.dataset.sendUrl;
        const verifyUrl = formOtp.dataset.verifyUrl;

        btnSend?.addEventListener('click', async () => {
            const email = emailInp.value;
            if(!email) return alert('Digite o email!');
            
            btnSend.textContent = 'ENVIANDO...';
            btnSend.disabled = true;

            try {
                const res = await fetch(sendUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || ''
                    },
                    body: JSON.stringify({ email })
                });
                const data = await res.json();
                
                if(data.success) {
                    step1.style.display = 'none';
                    step2.style.display = 'block';
                } else {
                    alert(data.message || 'Erro ao enviar.');
                }
            } catch (e) {
                alert('Erro de conexão.');
            }
            btnSend.textContent = 'ENVIAR CÓDIGO';
            btnSend.disabled = false;
        });

        btnVerify?.addEventListener('click', async () => {
            const email = emailInp.value;
            const code = codeInp.value;
            if(code.length !== 6) return alert('Código deve ter 6 dígitos.');
            
            btnVerify.textContent = 'VALIDANDO...';
            btnVerify.disabled = true;
            otpError.style.display = 'none';

            try {
                const res = await fetch(verifyUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || ''
                    },
                    body: JSON.stringify({ email, code })
                });
                const data = await res.json();
                
                if(data.success) {
                    btnVerify.textContent = 'SUCESSO! REDIRECIONANDO...';
                    window.location.href = data.redirect;
                } else {
                    otpError.textContent = data.message || 'Código inválido.';
                    otpError.style.display = 'block';
                    btnVerify.textContent = 'CONFIRMAR CÓDIGO';
                    btnVerify.disabled = false;
                }
            } catch (e) {
                otpError.textContent = 'Erro de conexão.';
                otpError.style.display = 'block';
                btnVerify.textContent = 'CONFIRMAR CÓDIGO';
                btnVerify.disabled = false;
            }
        });
    }

});

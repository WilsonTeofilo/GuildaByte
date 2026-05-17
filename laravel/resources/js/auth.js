/**
 * auth.js — Interações das telas de Login e Cadastro
 * Responsável por: multi-step, validação client-side, força da senha, toggle de senha.
 */
document.addEventListener('DOMContentLoaded', () => {

    // ── Toggle mostrar/ocultar senha ──────────────────────────────
    document.querySelectorAll('[data-toggle-password]').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.querySelector(btn.dataset.togglePassword);
            if (!input) return;
            const isHidden = input.type === 'password';
            input.type     = isHidden ? 'text' : 'password';
            btn.textContent = isHidden ? 'O' : 'S';
            btn.setAttribute('aria-label', isHidden ? 'Ocultar senha' : 'Mostrar senha');
        });
    });

    // ── Helpers de erro inline ────────────────────────────────────
    function showError(input, message) {
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
        input.style.borderColor = '';
        const existing = input.nextElementSibling;
        if (existing?.classList.contains('field-error')) existing.remove();
    }

    function clearAllErrors() {
        document.querySelectorAll('.field-error').forEach(e => e.remove());
        document.querySelectorAll('.pinp, .pixel-input').forEach(i => i.style.borderColor = '');
    }

    // ── Multi-step cadastro ───────────────────────────────────────
    const steps   = document.querySelectorAll('.step-panel');
    const xpFill  = document.getElementById('xpFill');
    const stepTxt = document.getElementById('stepTxt');
    const dot1    = document.getElementById('dot1');
    const dot2    = document.getElementById('dot2');

    if (!steps.length) return; // não está na tela de cadastro

    let current = 0;

    function goTo(index) {
        steps[current].classList.remove('active');
        current = index;
        steps[current].classList.add('active');
        clearAllErrors();

        const pct = current === 0 ? '50%' : '100%';
        if (xpFill)  xpFill.style.width  = pct;
        if (stepTxt) stepTxt.textContent  = `STEP ${current + 1} / ${steps.length}`;
        if (dot1)    dot1.classList.toggle('active', current === 0);
        if (dot2)    dot2.classList.toggle('active', current === 1);
    }

    /** Valida o Step 1 completamente antes de avançar */
    function validateStep1() {
        const name  = document.getElementById('name');
        const email = document.getElementById('email');
        const pwd   = document.getElementById('pwdInput');
        const conf  = document.getElementById('confirmPassword');
        let valid   = true;

        if (!name?.value.trim()) {
            showError(name, 'Informe seu nome completo.');
            valid = false;
        }

        const emailRx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email?.value.trim() || !emailRx.test(email.value)) {
            showError(email, 'Informe um e-mail válido.');
            valid = false;
        }

        if ((pwd?.value.length ?? 0) < 8) {
            showError(pwd, 'A senha deve ter pelo menos 8 caracteres.');
            valid = false;
        }

        if (pwd?.value && conf?.value && pwd.value !== conf.value) {
            showError(conf, 'As senhas não conferem. Verifique e tente novamente.');
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

    // Impede submit se as senhas ainda não batem (proteção dupla — server valida de novo)
    document.querySelector('[data-auth-form="register"]')?.addEventListener('submit', e => {
        const pwd  = document.getElementById('pwdInput');
        const conf = document.getElementById('confirmPassword');
        if (pwd?.value !== conf?.value) {
            e.preventDefault();
            showError(conf, 'As senhas não conferem. Corrija antes de enviar.');
            conf?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    // ── Password strength indicator ───────────────────────────────
    const pwdInput = document.getElementById('pwdInput');
    const confInput = document.getElementById('confirmPassword');
    const blocks = [
        document.getElementById('pb1'),
        document.getElementById('pb2'),
        document.getElementById('pb3'),
        document.getElementById('pb4'),
    ].filter(Boolean);

    if (pwdInput && blocks.length) {
        pwdInput.addEventListener('input', () => {
            const v = pwdInput.value;
            const score = [
                v.length >= 8,
                /[A-Z]/.test(v),
                /[0-9]/.test(v),
                /[^A-Za-z0-9]/.test(v),
            ].filter(Boolean).length;

            blocks.forEach((b, i) => {
                b.classList.toggle('active', i < score);
                b.style.background = score <= 1 ? '#ff7893'
                    : score === 2               ? '#f4a261'
                    : score === 3               ? '#e9c46a'
                    :                             '#92FFCB';
            });

            // Limpa erro de confirmação em tempo real se já confere
            if (confInput?.value && pwdInput.value === confInput.value) {
                clearError(confInput);
            }
        });

        // Valida confirmação em tempo real
        confInput?.addEventListener('input', () => {
            if (confInput.value && pwdInput.value !== confInput.value) {
                showError(confInput, 'Senhas não conferem.');
            } else {
                clearError(confInput);
            }
        });
    }

});

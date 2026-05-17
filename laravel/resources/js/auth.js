/**
 * auth.js — Interações das telas de Login e Cadastro
 */
document.addEventListener('DOMContentLoaded', () => {

    // ── Toggle mostrar/ocultar senha ──────────────────────────────
    document.querySelectorAll('[data-toggle-password]').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.querySelector(btn.dataset.togglePassword);
            if (!input) return;
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.textContent = show ? 'O' : 'S';
        });
    });

    // ── Multi-step cadastro ───────────────────────────────────────
    const steps    = document.querySelectorAll('.step-panel');
    const xpFill   = document.getElementById('xpFill');
    const stepTxt  = document.getElementById('stepTxt');
    const dot1     = document.getElementById('dot1');
    const dot2     = document.getElementById('dot2');

    if (!steps.length) return; // não está na tela de cadastro

    let current = 0;

    function goTo(index) {
        steps[current].classList.remove('active');
        current = index;
        steps[current].classList.add('active');

        const pct = current === 0 ? '50%' : '100%';
        if (xpFill)  xpFill.style.width = pct;
        if (stepTxt) stepTxt.textContent = `STEP ${current + 1} / ${steps.length}`;
        if (dot1)    dot1.classList.toggle('active', current === 0);
        if (dot2)    dot2.classList.toggle('active', current === 1);
    }

    // Validação simples do step 1 antes de avançar
    function step1Valid() {
        const name  = document.getElementById('name');
        const email = document.getElementById('email');
        const pwd   = document.getElementById('pwdInput');
        const conf  = document.getElementById('confirmPassword');

        if (!name?.value.trim())   { name?.focus();  return false; }
        if (!email?.value.trim())  { email?.focus(); return false; }
        if ((pwd?.value.length ?? 0) < 8) {
            pwd?.focus();
            return false;
        }
        if (pwd?.value !== conf?.value) {
            conf?.focus();
            return false;
        }
        return true;
    }

    document.querySelectorAll('[data-step-next]').forEach(btn => {
        btn.addEventListener('click', () => {
            if (current < steps.length - 1 && step1Valid()) goTo(current + 1);
        });
    });

    document.querySelectorAll('[data-step-prev]').forEach(btn => {
        btn.addEventListener('click', () => {
            if (current > 0) goTo(current - 1);
        });
    });

    // ── Password strength indicator ───────────────────────────────
    const pwdInput = document.getElementById('pwdInput');
    const blocks   = [
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
                b.style.background = score <= 1 ? '#e63946'
                    : score === 2               ? '#f4a261'
                    : score === 3               ? '#e9c46a'
                    :                             '#92FFCB';
            });
        });
    }

});

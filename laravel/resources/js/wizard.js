document.addEventListener('DOMContentLoaded', () => {
    const totalSteps = 5;
    let currentStep = 1;

    // Build Progress
    const progressContainer = document.getElementById('wizProgress');
    if (!progressContainer) return;

    for(let i=1; i<=totalSteps; i++){
        const d = document.createElement('div');
        d.className = 'wp-step' + (i === 1 ? ' active' : '');
        d.id = 'dot-' + i;
        d.textContent = i;
        progressContainer.appendChild(d);
    }

    const panels = document.querySelectorAll('.wizard-panel');
    const title = document.getElementById('wizTitle');
    const sub = document.getElementById('wizSub');
    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');

    window.selectPack = function(key) {
        document.getElementById('f_pack').value = key;
        document.querySelectorAll('.pack-card').forEach(c => c.classList.remove('selected'));
        event.currentTarget.classList.add('selected');
    };

    window.toggleFeat = function(el) {
        el.classList.toggle('active');
    };

    function updateView() {
        // UI
        panels.forEach(p => {
            const s = parseInt(p.dataset.step);
            p.classList.remove('active', 'prev');
            if(s === currentStep) {
                p.classList.add('active');
                title.textContent = p.dataset.title;
                sub.textContent = p.dataset.sub;
            } else if (s < currentStep) {
                p.classList.add('prev');
            }
        });

        // Dots
        for(let i=1; i<=totalSteps; i++){
            const d = document.getElementById('dot-'+i);
            if (d) {
                d.className = 'wp-step';
                if(i < currentStep) d.classList.add('done');
                if(i === currentStep) d.classList.add('active');
            }
        }

        // Buttons
        if (btnPrev) btnPrev.style.visibility = currentStep === 1 ? 'hidden' : 'visible';
        if (btnNext) btnNext.textContent = currentStep === totalSteps ? 'CONFIRMAR PEDIDO' : 'PRÓXIMO';

        // Preenche Resumo no ultimo passo
        if(currentStep === totalSteps) {
            document.getElementById('res_name').textContent = document.getElementById('f_projName').value || 'Projeto sem nome';
            document.getElementById('res_pack').textContent = document.getElementById('f_pack').value.toUpperCase();
            
            const hasIdSelect = document.getElementById('f_hasId');
            if (hasIdSelect) {
                document.getElementById('res_id').textContent = hasIdSelect.options[hasIdSelect.selectedIndex].text;
            }
        }
    }

    if (btnNext) {
        btnNext.addEventListener('click', () => {
            if(currentStep < totalSteps) {
                // Validacao simples passo 2
                if(currentStep === 2 && !document.getElementById('f_projName').value.trim()){
                    alert('Por favor, informe o nome do projeto.');
                    return;
                }
                currentStep++;
                updateView();
            } else {
                // Submit simulado do Snapshot!
                btnNext.textContent = 'PROCESSANDO...';
                btnNext.disabled = true;
                
                const wizardContainer = document.querySelector('.wizard-container');
                const storeUrl = wizardContainer.dataset.storeUrl;
                const csrfToken = wizardContainer.dataset.csrf;
                
                let features = [];
                document.querySelectorAll('.feat-item.active').forEach(f => features.push(f.textContent.trim()));

                // Aqui fazemos a req AJAX para Controller Store
                fetch(storeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        pack: document.getElementById('f_pack').value,
                        name: document.getElementById('f_projName').value,
                        desc: document.getElementById('f_projDesc').value,
                        features: features,
                        ref1: document.getElementById('f_ref1').value,
                        ref2: document.getElementById('f_ref2').value,
                        has_id: document.getElementById('f_hasId').value,
                    })
                }).then(res => res.json())
                  .then(data => {
                      if(data.success) {
                          window.location.href = data.redirect;
                      } else {
                          alert(data.message || 'Erro ao processar o pedido.');
                          btnNext.textContent = 'CONFIRMAR PEDIDO';
                          btnNext.disabled = false;
                      }
                  }).catch(e => {
                      alert('Erro de rede.');
                      btnNext.textContent = 'CONFIRMAR PEDIDO';
                      btnNext.disabled = false;
                  });
            }
        });
    }

    if (btnPrev) {
        btnPrev.addEventListener('click', () => {
            if(currentStep > 1) {
                currentStep--;
                updateView();
            }
        });
    }
});

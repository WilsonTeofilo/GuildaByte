/**
 * Interações da Landing Page da GuildaByte
 * - Loader
 * - Menu Toggle
 * - Seleção de Planos e Preview Interativo
 * - Navegação de Projetos no Modal (Carrossel)
 * - Modal de Autenticação (Briefing)
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Ocultar o loader assim que a página carregar
    window.addEventListener('load', () => {
        const loader = document.querySelector('.loader');
        if (loader) {
            setTimeout(() => {
                loader.classList.add('is-hidden');
            }, 800);
        }
    });

    // 2. Nav Toggle (Menu Mobile)
    const navToggle = document.querySelector('.nav-toggle');
    const mainNav = document.getElementById('main-nav');
    if (navToggle && mainNav) {
        navToggle.addEventListener('click', () => {
            const isOpen = mainNav.classList.contains('is-open');
            mainNav.classList.toggle('is-open', !isOpen);
            navToggle.setAttribute('aria-expanded', !isOpen);
        });
    }

    // Carregar dados dinâmicos injetados no HTML
    const contentScript = document.getElementById('guildabyte-content');
    let gbData = null;
    if (contentScript) {
        try {
            gbData = JSON.parse(contentScript.textContent);
        } catch (e) {
            console.error('Erro ao ler os dados do sistema:', e);
        }
    }

    // 3. Seleção de Pacotes (Planos)
    const planCards = document.querySelectorAll('.plan-card');
    const planButtons = document.querySelectorAll('.plan-select');
    
    // Elementos de UI a serem atualizados quando um plano muda
    const uiPlanName = document.getElementById('selected-plan-name');
    const uiPlanNote = document.getElementById('selected-plan-note');
    const uiPreviewPrice = document.getElementById('preview-price');
    const uiPreviewTitle = document.getElementById('preview-title');
    const uiPreviewDesc = document.getElementById('preview-description');

    const selectPlan = (planKey) => {
        if (!gbData || !gbData.plans[planKey]) return;
        const plan = gbData.plans[planKey];

        // Atualizar estilos dos cards
        planCards.forEach(card => {
            const isActive = card.getAttribute('data-plan-card') === planKey;
            card.classList.toggle('is-featured', isActive);
            card.setAttribute('aria-pressed', isActive);
        });

        // Atualizar textos no painel visual
        if (uiPlanName) uiPlanName.textContent = plan.label;
        if (uiPlanNote) uiPlanNote.textContent = plan.note;
        if (uiPreviewPrice) uiPreviewPrice.textContent = plan.price;
        if (uiPreviewTitle) uiPreviewTitle.textContent = plan.title;
        if (uiPreviewDesc) uiPreviewDesc.textContent = plan.description;

        // Salvar localmente para o front-end lembrar imediatamente
        localStorage.setItem('guildabyte_selected_plan', planKey);

        // Salvar via Laravel Session (AJAX) para persistência no back-end
        window.axios.post('/select-plan', { plan: planKey })
            .then(response => {
                console.log('Plano salvo no servidor:', response.data.plan);
            })
            .catch(error => {
                console.error('Erro ao salvar plano:', error);
            });
    };

    planButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const planKey = btn.getAttribute('data-plan');
            selectPlan(planKey);
            // Opcional: Rolar suavemente para a seção de briefing
            const briefing = document.getElementById('briefing');
            if (briefing) briefing.scrollIntoView({ behavior: 'smooth' });
        });
    });

    planCards.forEach(card => {
        card.addEventListener('click', () => {
            const planKey = card.getAttribute('data-plan-card');
            selectPlan(planKey);
        });
    });

    // Restaurar plano salvo (se existir)
    const savedPlan = localStorage.getItem('guildabyte_selected_plan');
    if (savedPlan && gbData && gbData.plans[savedPlan]) {
        selectPlan(savedPlan);
    }


    // 4. Painel de Status Interativo (Mini Board)
    const boardCards = document.querySelectorAll('.board-card');
    const previewStage = document.getElementById('preview-stage');
    const previewProgress = document.getElementById('preview-progress');
    const progressBar = document.getElementById('progress-bar');

    boardCards.forEach(card => {
        card.addEventListener('click', () => {
            boardCards.forEach(c => c.classList.remove('is-active'));
            card.classList.add('is-active');

            const stage = card.getAttribute('data-stage');
            const progress = card.getAttribute('data-progress');

            if (previewStage) previewStage.textContent = stage;
            if (previewProgress) previewProgress.textContent = progress + '%';
            if (progressBar) progressBar.style.width = progress + '%';
        });
    });

    // Iniciar a barra visual com o estágio ativo atual
    const activeBoard = document.querySelector('.board-card.is-active');
    if (activeBoard && progressBar) {
        progressBar.style.width = activeBoard.getAttribute('data-progress') + '%';
    }


    // 5. Lógica do Modal de Projetos (Carrossel)
    const projectModal = document.getElementById('project-modal');
    let currentProject = null;
    let currentSlideIndex = 0;

    // Elementos do Modal
    const mTitle = document.getElementById('project-modal-title');
    const mStage = document.getElementById('project-stage'); // Se quiser injetar imagens depois
    const mSlideTitle = document.getElementById('project-slide-title');
    const mSlideText = document.getElementById('project-slide-text');
    const mDots = document.getElementById('project-dots');
    const btnPrev = document.getElementById('project-prev');
    const btnNext = document.getElementById('project-next');

    const renderSlide = () => {
        if (!currentProject || !currentProject.slides) return;
        const slides = currentProject.slides;
        
        // Garantir limites
        if (currentSlideIndex < 0) currentSlideIndex = slides.length - 1;
        if (currentSlideIndex >= slides.length) currentSlideIndex = 0;

        const slide = slides[currentSlideIndex];
        
        if (mSlideTitle) mSlideTitle.textContent = slide.title;
        if (mSlideText) mSlideText.textContent = slide.text;

        // Renderizar a "janela dentro da janela" (Fake UI frame)
        if (mStage) {
            // Se houver uma imagem definida no slide, carrega, senao um placeholder cinza escuro
            const innerContent = slide.image 
                ? `<img src="${slide.image}" alt="${slide.title}" style="width: 100%; height: 100%; object-fit: cover; display: block;">` 
                : `<div style="width: 100%; height: 100%; background: #13131c; display: flex; align-items: center; justify-content: center; color: #534ab7; font-family: monospace;">[ ${slide.title} UI AREA ]</div>`;

            mStage.innerHTML = `
                <div class="fake-ui-window" style="width: 100%; height: 100%; border: 1px solid var(--line); border-radius: 8px; background: #050507; overflow: hidden; display: flex; flex-direction: column;">
                    <div class="fake-ui-titlebar" style="height: 24px; background: rgba(255,255,255,0.05); border-bottom: 1px solid var(--line); display: flex; align-items: center; padding: 0 8px; gap: 4px;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #ff7893;"></span>
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #f4a261;"></span>
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #92ffcb;"></span>
                        <span style="margin-left: auto; margin-right: auto; font-family: 'Press Start 2P', monospace; font-size: 8px; color: var(--muted);">${currentProject.name} - ${slide.title}</span>
                    </div>
                    <div class="fake-ui-body" style="flex: 1; position: relative; overflow: hidden;">
                        ${innerContent}
                    </div>
                </div>
            `;
        }

        // Atualizar dots
        if (mDots) {
            Array.from(mDots.children).forEach((dot, index) => {
                dot.classList.toggle('is-active', index === currentSlideIndex);
            });
        }
    };

    const openProject = (projectKey) => {
        if (!gbData || !gbData.projects[projectKey]) return;
        currentProject = gbData.projects[projectKey];
        currentSlideIndex = 0;

        if (mTitle) mTitle.textContent = currentProject.title;
        
        // Gerar dots
        if (mDots && currentProject.slides) {
            mDots.innerHTML = '';
            currentProject.slides.forEach((_, index) => {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = index === 0 ? 'is-active' : '';
                dot.addEventListener('click', () => {
                    currentSlideIndex = index;
                    renderSlide();
                });
                mDots.appendChild(dot);
            });
        }

        renderSlide();
        if (projectModal) {
            projectModal.removeAttribute('aria-hidden');
            projectModal.classList.add('is-open');
            document.body.classList.add('menu-open'); // Travar scroll
        }
    };

    if (projectModal) {
        // Abrir
        const openButtons = document.querySelectorAll('[data-project-open]');
        openButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                openProject(btn.getAttribute('data-project-open'));
            });
        });

        // Fechar
        const closeButtons = projectModal.querySelectorAll('[data-project-close]');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                projectModal.setAttribute('aria-hidden', 'true');
                projectModal.classList.remove('is-open');
                document.body.classList.remove('menu-open');
                currentProject = null;
            });
        });

        // Navegação
        if (btnPrev) {
            btnPrev.addEventListener('click', () => {
                currentSlideIndex--;
                renderSlide();
            });
        }
        if (btnNext) {
            btnNext.addEventListener('click', () => {
                currentSlideIndex++;
                renderSlide();
            });
        }
    }


    // 6. Modal de Auth Choice (Para rotear com pacote salvo)
    const authChoiceModal = document.getElementById('auth-choice-modal');
    if (authChoiceModal) {
        const closeButtons = authChoiceModal.querySelectorAll('[data-auth-choice-close]');
        const formSubmits = document.querySelectorAll('.form-submit');
        
        formSubmits.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Mostrar qual plano o cara está escolhendo no modal de Auth
                const savedPlanKey = localStorage.getItem('guildabyte_selected_plan') || 'core';
                const authPlanEl = document.getElementById('auth-choice-plan');
                if (authPlanEl && gbData && gbData.plans[savedPlanKey]) {
                    authPlanEl.textContent = `Plano vinculado: ${gbData.plans[savedPlanKey].label}`;
                }

                authChoiceModal.removeAttribute('aria-hidden');
                authChoiceModal.classList.add('is-open');
                document.body.classList.add('menu-open');
            });
        });

        closeButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                authChoiceModal.setAttribute('aria-hidden', 'true');
                authChoiceModal.classList.remove('is-open');
                document.body.classList.remove('menu-open');
            });
        });

        // Botões de ação dentro do modal
        const btnLogin = document.querySelector('[data-auth-choice-login]');
        const btnRegister = document.querySelector('[data-auth-choice-register]');

        if (btnLogin) {
            btnLogin.addEventListener('click', () => {
                window.location.href = gbData ? gbData.routes.login : '/login';
            });
        }

        if (btnRegister) {
            btnRegister.addEventListener('click', () => {
                window.location.href = gbData ? gbData.routes.register : '/register';
            });
        }
    }
});

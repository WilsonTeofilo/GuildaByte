@extends('layouts.client')

@section('content')
<style>
/* CSS do Wizard — Baseado na referência UI V2 */
.wizard-container {
    display: flex;
    flex-direction: column;
    height: 100%;
    min-height: 70vh;
    position: relative;
    overflow: hidden;
}

.wizard-header {
    margin-bottom: 24px;
}

.wizard-eyebrow {
    font-family: 'Press Start 2P', monospace;
    font-size: 8px;
    color: var(--gb-purple);
    letter-spacing: 0.12em;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.wizard-eyebrow::before {
    content: '';
    width: 6px;
    height: 6px;
    background: var(--gb-green);
    display: inline-block;
}

.wizard-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--gb-text);
    line-height: 1.2;
}

.wizard-sub {
    font-size: 14px;
    color: var(--gb-muted);
    margin-top: 4px;
}

/* Timeline Topo */
.wizard-progress {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 32px;
    position: relative;
    padding: 0 10px;
}
.wizard-progress::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 20px;
    right: 20px;
    height: 2px;
    background: var(--gb-border);
    z-index: 0;
}
.wp-step {
    width: 28px;
    height: 28px;
    background: var(--gb-surface-2);
    border: 2px solid var(--gb-border);
    color: var(--gb-muted);
    font-family: 'Press Start 2P', monospace;
    font-size: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
    transition: all 0.3s;
}
.wp-step.active {
    background: rgba(127,119,221,0.2);
    border-color: var(--gb-purple);
    color: var(--gb-purple-light);
}
.wp-step.done {
    background: var(--gb-purple);
    border-color: var(--gb-purple);
    color: var(--gb-surface-1);
}

/* Painel de Conteúdo com transição */
.wizard-stage {
    flex: 1;
    position: relative;
    overflow: hidden;
    min-height: 400px;
}

.wizard-panel {
    position: absolute;
    top: 0; left: 0; right: 0;
    opacity: 0;
    transform: translateX(30px);
    pointer-events: none;
    transition: opacity 0.4s ease, transform 0.4s ease;
}
.wizard-panel.active {
    opacity: 1;
    transform: translateX(0);
    pointer-events: auto;
    position: relative;
}
.wizard-panel.prev {
    transform: translateX(-30px);
}

/* Cartões de Pacote (Step 1) */
.pack-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
    margin-top: 16px;
}
.pack-card {
    background: var(--gb-surface-2);
    border: 2px solid var(--gb-border);
    padding: 20px;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
}
.pack-card:hover {
    border-color: var(--gb-purple);
    background: rgba(127,119,221,0.05);
}
.pack-card.selected {
    border-color: var(--gb-green);
    background: rgba(146,255,203,0.05);
}
.pack-card.selected::after {
    content: '';
    position: absolute;
    top: 10px; right: 10px;
    width: 12px; height: 12px;
    background: var(--gb-green);
    box-shadow: 0 0 10px var(--gb-green);
}
.pack-name {
    font-family: 'Press Start 2P', monospace;
    font-size: 8px;
    color: var(--gb-purple-light);
    letter-spacing: 0.1em;
}
.pack-card.selected .pack-name { color: var(--gb-green); }
.pack-price {
    font-size: 24px;
    font-weight: 700;
    color: var(--gb-text);
    margin: 8px 0;
}
.pack-price small { font-size: 12px; color: var(--gb-muted); font-weight: 400; }
.pack-desc {
    font-size: 12px;
    color: var(--gb-muted);
    line-height: 1.5;
    margin-bottom: 16px;
}
.pack-items li {
    font-size: 12px;
    color: var(--gb-text);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.pack-items li::before {
    content: '▶';
    font-size: 8px;
    color: var(--gb-purple);
}

/* Formulários */
.wiz-fgrp {
    margin-bottom: 20px;
}
.wiz-lbl {
    display: block;
    font-family: 'Press Start 2P', monospace;
    font-size: 8px;
    color: var(--gb-text);
    margin-bottom: 10px;
    letter-spacing: 0.1em;
}
.wiz-inp, .wiz-textarea {
    width: 100%;
    background: var(--gb-surface-2);
    border: 1px solid var(--gb-border);
    color: var(--gb-text);
    padding: 12px 16px;
    font-family: 'Space Grotesk', sans-serif;
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s;
}
.wiz-inp:focus, .wiz-textarea:focus {
    border-color: var(--gb-purple);
}
.wiz-textarea {
    resize: vertical;
    min-height: 100px;
}

/* Features Grid (Step 3) */
.feat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 12px;
}
.feat-item {
    background: var(--gb-surface-2);
    border: 1px solid var(--gb-border);
    padding: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s;
}
.feat-item.active {
    border-color: var(--gb-purple);
    background: rgba(127,119,221,0.1);
    color: var(--gb-purple-light);
}
.feat-box {
    width: 16px; height: 16px;
    border: 1px solid var(--gb-border);
    display: flex; align-items: center; justify-content: center;
}
.feat-item.active .feat-box {
    background: var(--gb-purple);
    border-color: var(--gb-purple);
}
.feat-item.active .feat-box::after {
    content: '✓';
    font-size: 10px; color: #fff;
}

/* Barra de Navegação Inferior */
.wizard-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 24px;
    border-top: 1px solid var(--gb-border);
    margin-top: 24px;
}

.w-btn {
    padding: 12px 24px;
    font-family: 'Press Start 2P', monospace;
    font-size: 8px;
    letter-spacing: 0.1em;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
}
.w-btn.prev {
    background: transparent;
    color: var(--gb-muted);
    border: 1px solid var(--gb-border);
}
.w-btn.prev:hover {
    color: var(--gb-text);
    border-color: var(--gb-muted);
}
.w-btn.next {
    background: var(--gb-green);
    color: var(--gb-bg);
}
.w-btn.next:hover {
    background: var(--gb-green-dim);
}
</style>

<div class="wizard-container">
    <div class="wizard-header">
        <div class="wizard-eyebrow">NOVO PEDIDO</div>
        <h1 class="wizard-title" id="wizTitle">Escolher pacote</h1>
        <p class="wizard-sub" id="wizSub">Qual é o nível do seu projeto?</p>
    </div>

    <div class="wizard-progress" id="wizProgress">
        <!-- JS gera os passos -->
    </div>

    <div class="wizard-stage" id="wizStage">
        <!-- STEP 1: Pacote -->
        <div class="wizard-panel active" data-step="1" data-title="Escolher pacote" data-sub="Defina o escopo base do projeto.">
            <div class="pack-grid">
                @foreach($plans as $key => $plan)
                <div class="pack-card {{ $preSelectedPlan === $key ? 'selected' : '' }}" onclick="selectPack('{{ $key }}')">
                    <div class="pack-name">{{ strtoupper($plan['name']) }}</div>
                    <div class="pack-price">{{ $plan['price'] }} <small>setup</small></div>
                    <p class="pack-desc">{{ $plan['summary'] }}</p>
                    <ul class="pack-items">
                        @foreach($plan['items'] as $item)
                        <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
            <input type="hidden" id="f_pack" value="{{ $preSelectedPlan }}">
        </div>

        <!-- STEP 2: Briefing Básico -->
        <div class="wizard-panel" data-step="2" data-title="O Negócio" data-sub="Fale um pouco sobre a sua empresa.">
            <div class="wiz-fgrp">
                <label class="wiz-lbl">Qual o nome do projeto/empresa?</label>
                <input type="text" id="f_projName" class="wiz-inp" placeholder="Ex: Barbearia Elite">
            </div>
            <div class="wiz-fgrp">
                <label class="wiz-lbl">Descreva o que a sua empresa faz em poucas palavras:</label>
                <textarea id="f_projDesc" class="wiz-textarea" placeholder="Ex: Somos uma barbearia clássica focada no público executivo..."></textarea>
            </div>
        </div>

        <!-- STEP 3: Objetivos -->
        <div class="wizard-panel" data-step="3" data-title="Objetivo Principal" data-sub="O que este sistema precisa resolver?">
            <div class="feat-grid">
                <div class="feat-item" onclick="toggleFeat(this)">
                    <div class="feat-box"></div>
                    <span>Vender Produtos (E-commerce)</span>
                </div>
                <div class="feat-item" onclick="toggleFeat(this)">
                    <div class="feat-box"></div>
                    <span>Agendamento de Serviços</span>
                </div>
                <div class="feat-item" onclick="toggleFeat(this)">
                    <div class="feat-box"></div>
                    <span>Captar Leads / Contatos</span>
                </div>
                <div class="feat-item" onclick="toggleFeat(this)">
                    <div class="feat-box"></div>
                    <span>Institucional / Portfólio</span>
                </div>
                <div class="feat-item" onclick="toggleFeat(this)">
                    <div class="feat-box"></div>
                    <span>Painel Administrativo Interno</span>
                </div>
            </div>
        </div>

        <!-- STEP 4: Referências -->
        <div class="wizard-panel" data-step="4" data-title="Referências Visuais" data-sub="Links de sites que você acha bonitos (opcional).">
            <div class="wiz-fgrp">
                <label class="wiz-lbl">Link 1 (Concorrente ou Inspiração)</label>
                <input type="text" id="f_ref1" class="wiz-inp" placeholder="https://...">
            </div>
            <div class="wiz-fgrp">
                <label class="wiz-lbl">Link 2</label>
                <input type="text" id="f_ref2" class="wiz-inp" placeholder="https://...">
            </div>
            <div class="wiz-fgrp">
                <label class="wiz-lbl">Você já possui identidade visual (Logo, Cores)?</label>
                <select id="f_hasId" class="wiz-inp">
                    <option value="sim">Sim, já tenho manual e logo.</option>
                    <option value="nao">Não, preciso que a GuildaByte crie o logo.</option>
                    <option value="ideia">Tenho uma ideia, mas não tenho arquivos finais.</option>
                </select>
            </div>
        </div>

        <!-- STEP 5: Resumo e Snapshot -->
        <div class="wizard-panel" data-step="5" data-title="Confirmar Snapshot" data-sub="Revise as informações antes de abrir o pedido formal.">
            <div class="gb-card p-6 border-l-4 border-l-[#92ffcb]" style="background: var(--gb-surface-2)">
                <h3 class="text-xl font-bold mb-4" id="res_name">Nome do Projeto</h3>
                <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                    <div>
                        <span class="text-[#aaa4bc] block mb-1">Pacote Selecionado:</span>
                        <strong class="text-[#7f77dd] uppercase" id="res_pack">START</strong>
                    </div>
                    <div>
                        <span class="text-[#aaa4bc] block mb-1">Status de Identidade:</span>
                        <strong class="text-white" id="res_id">Já possui</strong>
                    </div>
                </div>
                
                <div class="p-4 bg-[#0a0a10] border border-[#26215c] rounded">
                    <p class="text-xs text-[#aaa4bc] mb-2 leading-relaxed">
                        ⚠️ <strong class="text-[#f9cb42]">Aviso de Snapshot:</strong> 
                        Ao clicar em "Confirmar Pedido", o valor base do pacote selecionado será gravado imutavelmente. 
                        Este será o nosso ponto de partida para a proposta formal que enviaremos a você após analisarmos os detalhes.
                        Você não pagará nada agora.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="wizard-footer">
        <button class="w-btn prev" id="btnPrev" style="visibility: hidden;">VOLTAR</button>
        <button class="w-btn next" id="btnNext">PRÓXIMO</button>
    </div>
</div>

<script>
    const totalSteps = 5;
    let currentStep = 1;

    // Build Progress
    const progressContainer = document.getElementById('wizProgress');
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

    function selectPack(key) {
        document.getElementById('f_pack').value = key;
        document.querySelectorAll('.pack-card').forEach(c => c.classList.remove('selected'));
        event.currentTarget.classList.add('selected');
    }

    function toggleFeat(el) {
        el.classList.toggle('active');
    }

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
            d.className = 'wp-step';
            if(i < currentStep) d.classList.add('done');
            if(i === currentStep) d.classList.add('active');
        }

        // Buttons
        btnPrev.style.visibility = currentStep === 1 ? 'hidden' : 'visible';
        btnNext.textContent = currentStep === totalSteps ? 'CONFIRMAR PEDIDO' : 'PRÓXIMO';

        // Preenche Resumo no ultimo passo
        if(currentStep === totalSteps) {
            document.getElementById('res_name').textContent = document.getElementById('f_projName').value || 'Projeto sem nome';
            document.getElementById('res_pack').textContent = document.getElementById('f_pack').value.toUpperCase();
            document.getElementById('res_id').textContent = document.getElementById('f_hasId').options[document.getElementById('f_hasId').selectedIndex].text;
        }
    }

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
            
            // Aqui fazemos a req AJAX para Controller Store
            fetch('{{ route('client.wizard.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    pack: document.getElementById('f_pack').value,
                    name: document.getElementById('f_projName').value,
                    desc: document.getElementById('f_projDesc').value,
                })
            }).then(res => res.json())
              .then(data => {
                  if(data.success) {
                      window.location.href = data.redirect;
                  }
              });
        }
    });

    btnPrev.addEventListener('click', () => {
        if(currentStep > 1) {
            currentStep--;
            updateView();
        }
    });
</script>
@endsection

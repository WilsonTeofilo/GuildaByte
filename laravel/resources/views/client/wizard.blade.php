@extends('layouts.client')

@section('title', 'Novo Pedido | GuildaByte')

@push('styles')
    <link rel="stylesheet" href="{{ asset('style/client.css') }}?v=1.1">
@endpush

@section('content')
<div class="wizard-container" data-store-url="{{ route('client.wizard.store') }}" data-csrf="{{ csrf_token() }}">
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
            <div class="gb-card p-6 border-l-4 border-l-[#92ffcb] bg-[#101018]">
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
        <button class="w-btn prev hidden" id="btnPrev">VOLTAR</button>
        <button class="w-btn next" id="btnNext">PRÓXIMO</button>
    </div>
</div>

<script src="{{ asset('js/wizard.js') }}?v=1.0"></script>
@endsection

GuildaByte - backlog arquitetural obrigatorio

Este arquivo existe para impedir que regras criticas fiquem perdidas no chat.

Principios fixos
- Backend e a fonte da verdade.
- Front-end nunca decide preco, desconto, comissao, permissao, aceite, pagamento ou status sensivel.
- Dinheiro deve ser armazenado em centavos.
- Pacote tem preco atual; projeto tem preco acordado.
- Projeto criado nunca recalcula valor automaticamente pelo preco atual do pacote.
- Toda alteracao financeira sensivel exige permissao, motivo e log.
- Token sensivel usado morre.
- Token novo revoga token antigo equivalente.
- Sessao nao e infinita.

Missao Admin Root - painel de dono

O Admin Root precisa visualizar:
- receita total;
- receita do mes;
- receita recorrente mensal;
- projetos ativos, atrasados, em negociacao, entregues e encerrados;
- suportes abertos e manutencoes pendentes;
- funcionarios ativos, ocupados, suspensos e disponiveis;
- clientes ativos, inadimplentes e inativos;
- pacotes mais vendidos;
- promocoes ativas;
- bonus pendentes;
- comissoes a pagar;
- taxa GuildaByte acumulada;
- logs e auditoria.

Menu Admin Root:
- Dashboard;
- Projetos;
- Clientes;
- Pacotes;
- Promocoes;
- Campanhas;
- Financeiro;
- Funcionarios;
- Comissoes;
- Bonus;
- Suporte;
- Manutencoes;
- Ranking;
- Guilda Board;
- Contratos;
- LGPD;
- Auditoria;
- Configuracoes;
- Seguranca.

Missao Financeiro - snapshot e auditoria

Regra de ouro:
- Preco acordado vira snapshot e nunca muda sozinho.
- Promocao nova vale apenas para novos pedidos.
- Comissao usa valor acordado, nao preco vivo do pacote.
- Alteracao manual em projeto existente so por aditivo/log.

Fluxo obrigatorio:
1. Admin altera preco do pacote.
2. Sistema cria nova package_version.
3. Novos pedidos usam a versao atual.
4. Pedido/projeto grava snapshot.
5. Promocao posterior nao altera projeto antigo.

Ja iniciado no Laravel:
- packages;
- package_versions;
- promotions;
- promotion_packages;
- project_requests;
- projects com agreed_*_cents;
- project_financial_events;
- project_members;
- CalculateProjectPricingSnapshot com teste.

Campos centrais do projeto:
- agreed_package_name;
- agreed_package_version;
- agreed_base_value_cents;
- agreed_discount_value_cents;
- agreed_final_value_cents;
- guildabyte_fee_basis_points;
- guildabyte_fee_value_cents;
- team_net_value_cents;
- deadline_days_snapshot.

Missao Promocoes e campanhas

Admin Root deve poder:
- criar promocao;
- agendar promocao;
- selecionar pacote/versao;
- definir desconto fixo, percentual ou preco final;
- pre-visualizar impacto;
- ativar, pausar, encerrar e duplicar promocao;
- enviar campanha por email e WhatsApp;
- ver resultados.

Regra de campanha:
- promocao e marketing;
- comunicacao de projeto nao e marketing;
- respeitar consentimento de email/WhatsApp;
- nao enviar promocao para cliente inadimplente;
- nao enviar promocao para cliente que comprou pacote equivalente nos ultimos 30 dias, salvo decisao manual.

Missao Area do Cliente MVP

Cliente deve conseguir:
- criar conta;
- solicitar projeto;
- acompanhar status macro;
- ver proposta;
- aceitar ou recusar proposta;
- enviar arquivos;
- enviar mensagem formal;
- abrir suporte;
- ver pagamentos e mensalidade;
- aprovar etapas;
- alterar preferencias de comunicacao.

Cliente nao pode ver:
- comissao;
- taxa interna detalhada;
- chat interno;
- logs sensiveis;
- tarefas internas;
- dados de outro cliente;
- financeiro da equipe.

Telas cliente:
- Dashboard;
- Meus Projetos;
- Novo Pedido;
- Propostas;
- Suporte;
- Mensalidade;
- Arquivos;
- Mensagens;
- Reunioes;
- Perfil.

UX cliente:
- mobile-first;
- uma acao principal por tela;
- pendencia no topo;
- status sempre visivel;
- linguagem simples;
- pixel/RPG apenas como detalhe.

Missao Ranking e bonus

Ranking nao pode ser hardcoded.
Admin Root cria eventos de premiacao.

Categorias iniciais:
- Landing Pages;
- Sistemas Core/Custom;
- Suporte e Manutencao;
- Melhor da Guilda;
- Custom.

Tipos:
- goal_based;
- ranking_based.

Ja iniciado no Laravel:
- reward_events;
- reward_event_packages;
- reward_scores;
- reward_winners.

Funcionario pode ver:
- propria posicao;
- propria pontuacao;
- top 5 por categoria;
- requisitos faltantes;
- historico de premios.

Funcionario nao pode ver:
- comissao de outras pessoas;
- dados financeiros internos;
- avaliacoes privadas detalhadas.

Missao Guilda Board

Guilda Board e o centro operacional, mas o cliente ve somente timeline/status/aprovacoes oficiais.

Modulos futuros:
- board por projeto;
- colunas;
- cards;
- checklists;
- comentarios;
- anexos;
- logs;
- chat interno;
- chat com cliente;
- call do projeto;
- reunioes;
- mural de projetos;
- mural de manutencoes;
- mural de funcionalidades extras.

Call e tela:
- comecar com Jitsi/Meet integrado;
- nao construir WebRTC proprio no MVP;
- controlar permissao da sala no backend;
- registrar historico de reuniao;
- links sensiveis devem expirar.

Missao Seguranca profunda

Sessoes:
- cookie HttpOnly;
- Secure em producao;
- SameSite Lax ou Strict;
- regenerar session_id apos login;
- expirar por inatividade;
- logout real;
- logout global;
- revogar sessao em troca de senha, suspensao ou incidente.

Tokens:
- salvar somente hash;
- expiracao curta;
- uso unico;
- novo token revoga antigo equivalente;
- token usado recebe used_at;
- token revogado recebe revoked_at.

Acao sensivel exige backend, permissao, CSRF e log.
Pode exigir senha recente ou 2FA:
- alterar preco;
- criar promocao;
- alterar comissao;
- marcar pagamento como pago;
- criar admin;
- alterar permissao;
- exportar dados LGPD;
- baixar arquivo sensivel;
- trocar email;
- trocar senha.

Pagamentos:
- front-end nunca valida pagamento;
- redirect de sucesso nao prova pagamento;
- webhook validado prova pagamento;
- backend confere valor, pedido e status;
- webhook duplicado nao duplica financeiro.

IDOR:
- toda rota com ID valida dono/permissao no backend;
- cliente so acessa dados dele;
- funcionario so acessa projeto em que participa;
- admin acessa conforme permissao.

Upload:
- validar extensao;
- validar MIME real;
- limitar tamanho;
- renomear arquivo;
- bloquear execucao;
- controlar download por permissao;
- logar arquivo sensivel.

Ordem recomendada daqui pra frente
1. Finalizar auth Laravel real com sessoes seguras.
2. Construir layout base Blade/Tailwind da area cliente.
3. Implementar fluxo Novo Pedido usando packages/package_versions.
4. Criar Admin Root basico para pacotes e versoes.
5. Criar propostas com aceite e snapshot.
6. Criar detalhe do projeto cliente.
7. Criar financeiro basico e project_financial_events.
8. Criar promocoes e campanhas.
9. Criar suporte/mensalidade.
10. Criar ranking e bonus.
11. Criar Guilda Board.
12. Integrar calls/reunioes.

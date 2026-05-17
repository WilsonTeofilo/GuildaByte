#!/bin/bash
# =============================================================
# GuildaByte — Script de Deploy (Produção)
# Execute: bash deploy.sh
# =============================================================
# ⚠️  CHECKLIST ANTES DE EXECUTAR:
#   1. Remover DevUsersSeeder (ou não rodar seed em produção)
#   2. Garantir SESSION_SECURE_COOKIE=true no .env do servidor
#   3. Confirmar que APP_ENV=production no .env do servidor
# =============================================================

set -e  # Para na primeira falha

echo "🚀 Iniciando deploy GuildaByte..."

# 1. Baixar dependências de produção (sem devtools)
composer install --no-dev --optimize-autoloader

# 2. Build do frontend (Vite — CSS/JS minificados com purge)
npm ci
npm run build

# 3. Otimizações do Laravel (caches obrigatórios em produção)
php artisan config:cache    # Combina todos os configs em um arquivo
php artisan route:cache     # Pré-compila todas as rotas
php artisan view:cache      # Pré-compila todos os Blade templates
php artisan event:cache     # Pré-compila os listeners de eventos
php artisan optimize        # Atalho que roda os 4 acima + class loader

# 4. Executar migrations sem interação
php artisan migrate --force

# 5. Limpar storage público
php artisan storage:link 2>/dev/null || true

echo ""
echo "✅ Deploy concluído! GuildaByte está no ar."
echo "-----------------------------------------------"
echo "Lembre-se:"
echo "  → Deletar usuários de teste (DevUsersSeeder)"
echo "  → Verificar SESSION_SECURE_COOKIE=true"
echo "  → Verificar APP_ENV=production"
echo "-----------------------------------------------"

GuildaByte - Stack local

Objetivo
Manter a nova base Laravel isolada, testavel e segura, sem misturar regra nova no PHP legado.

Stack configurada
- PHP 8.2 via XAMPP.
- Composer.
- Laravel 12 em /laravel.
- Blade.
- Tailwind CSS 4 via Vite.
- JavaScript via Vite.
- PostgreSQL 16 local via Docker.
- phpPgAdmin local via Docker.

URLs locais
- Laravel: http://127.0.0.1:8000
- Vite: http://127.0.0.1:5173
- phpPgAdmin: http://127.0.0.1:8080
- PostgreSQL: 127.0.0.1:5432

Comandos principais

Subir banco:
docker compose up -d db phppgadmin

Ver containers:
docker ps --filter "name=guildab"

Rodar migrations:
cd laravel
C:\xampp\php\php.exe artisan migrate

Rodar testes:
cd laravel
C:\xampp\php\php.exe artisan test

Build frontend:
cd laravel
npm run build

Dev frontend:
cd laravel
npm run dev

Servidor Laravel:
cd laravel
C:\xampp\php\php.exe artisan serve --host=127.0.0.1 --port=8000

Regras de seguranca
- O arquivo /laravel/.env nao deve ir para o Git.
- O arquivo /.env nao deve ir para o Git.
- Use /laravel/.env.example como referencia sem segredo real.
- SQL deve ficar em migrations, models, repositories ou services, nunca em Blade.
- Blade mostra interface, mas regra sensivel fica no backend.
- Front-end pode prever UI, nunca decidir preco, permissao, comissao, aceite ou pagamento.

Direcao arquitetural
- app/Http/Controllers: entrada HTTP.
- app/Actions ou app/UseCases: casos de uso.
- app/Domain: regras de dominio quando a regra crescer.
- app/Repositories: acesso a dados quando o Eloquent direto ficar repetitivo.
- app/Services: integracoes e servicos de aplicacao.
- resources/views: Blade.
- resources/css e resources/js: assets compilados pelo Vite.
- database/migrations: schema versionado.

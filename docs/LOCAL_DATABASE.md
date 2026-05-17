# Banco local da GuildaByte

O banco local oficial e PostgreSQL via Docker.
Nao use Neon para desenvolvimento diario e nao volte o legado para MySQL.

## Subir banco local

```powershell
docker compose up -d
cd laravel
C:\xampp\php\php.exe artisan migrate
C:\xampp\php\php.exe artisan db:seed
```

## Config local

Arquivo raiz `.env` e `laravel/.env` devem apontar para:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=guildabyte
DB_USERNAME=guildabyte
DB_PASSWORD=guildabyte_local_only
```

## Validar

Legado temporario:

```powershell
Invoke-WebRequest -UseBasicParsing http://localhost/GuildaB/Cliente/healthcheck.php
```

Laravel:

```powershell
cd laravel
C:\xampp\php\php.exe artisan migrate:status
C:\xampp\php\php.exe artisan test
```

## Regra

Schema SQL manual antigo foi removido para evitar dupla fonte da verdade.
A fonte da verdade agora sao as migrations em `laravel/database/migrations`.

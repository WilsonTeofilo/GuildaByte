<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

// Incinera a tabela de troca de e-mail. E-mail é a chave de login — não pode ser alterado.
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('email_change_requests');
    }

    public function down(): void
    {
        // Sem volta. Decisão arquitetural.
    }
};

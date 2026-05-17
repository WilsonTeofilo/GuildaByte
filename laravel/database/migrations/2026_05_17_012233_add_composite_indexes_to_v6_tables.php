<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Índices compostos para as tabelas mais consultadas do sistema.
 * Separado em migração própria para ser fácil de revisar e ajustar.
 *
 * Regra: indexar todo campo que aparece em WHERE, JOIN ou ORDER BY frequentes.
 */
return new class extends Migration
{
    public function up(): void
    {
        // projects — consultado constantemente por cliente + status
        Schema::table('projects', function (Blueprint $table) {
            $table->index(['client_id', 'status']);
            $table->index(['status', 'financial_status']);
            $table->index('created_at');
        });

        // package_versions — sempre buscado pelo pacote + versão atual
        Schema::table('package_versions', function (Blueprint $table) {
            $table->index(['package_id', 'is_current']);
            $table->index('valid_from');
        });

        // support_tickets — consultas de suporte por cliente e status
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->index(['client_id', 'status']);
            $table->index(['project_id', 'status']);
        });

        // payments — consultas financeiras por cliente e status
        Schema::table('payments', function (Blueprint $table) {
            $table->index(['client_id', 'status']);
        });

        // commissions — consultas por funcionário + status de pagamento
        Schema::table('commissions', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
        });

        // reward_scores — ranking ordenado por usuário
        Schema::table('reward_scores', function (Blueprint $table) {
            $table->index(['reward_event_id', 'user_id']);
        });

        // cards — kanban ordenado por coluna + posição
        Schema::table('cards', function (Blueprint $table) {
            $table->index(['board_column_id', 'position']);
        });

        // audit_logs — auditoria por usuário e tipo de ação
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index(['user_id', 'action']);
            $table->index('created_at');
        });

        // security_events — logs de segurança por usuário e tipo
        Schema::table('security_events', function (Blueprint $table) {
            $table->index(['user_id', 'event_type']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('projects', fn($t) => $t->dropIndex(['client_id', 'status']));
        Schema::table('package_versions', fn($t) => $t->dropIndex(['package_id', 'is_current']));
        Schema::table('support_tickets', fn($t) => $t->dropIndex(['client_id', 'status']));
        Schema::table('payments', fn($t) => $t->dropIndex(['client_id', 'status']));
        Schema::table('commissions', fn($t) => $t->dropIndex(['user_id', 'status']));
        Schema::table('reward_scores', fn($t) => $t->dropIndex(['reward_event_id', 'user_id']));
        Schema::table('cards', fn($t) => $t->dropIndex(['board_column_id', 'position']));
        Schema::table('audit_logs', fn($t) => $t->dropIndex(['user_id', 'action']));
        Schema::table('security_events', fn($t) => $t->dropIndex(['user_id', 'event_type']));
    }
};

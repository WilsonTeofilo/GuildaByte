<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Alterar proposals
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropForeign(['project_request_id']);
            $table->renameColumn('project_request_id', 'project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });

        // Alterar contract_acceptances para linkar direto ao projeto (OpÃ§Ã£o B)
        Schema::table('contract_acceptances', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
            $table->foreignId('project_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });
        
        // Remove contract_id agora que a chave estrangeira foi removida
        Schema::table('contract_acceptances', function (Blueprint $table) {
            $table->dropColumn('contract_id');
        });
    }

    public function down(): void
    {
        Schema::table('contract_acceptances', function (Blueprint $table) {
            $table->foreignId('contract_id')->nullable()->constrained()->onDelete('cascade');
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
        });

        Schema::table('proposals', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->renameColumn('project_id', 'project_request_id');
            $table->foreign('project_request_id')->references('id')->on('project_requests')->onDelete('cascade');
        });
    }
};

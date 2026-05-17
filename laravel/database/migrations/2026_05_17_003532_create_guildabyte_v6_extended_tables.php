<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // === SKILLS ===
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // language, framework, database, tool
            $table->timestamps();
        });

        Schema::create('employee_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('declared'); // declared, validating, validated
            $table->timestamps();
        });

        // === PROJECT REQUESTS & PROPOSALS ===
        Schema::create('project_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('package_id')->nullable()->constrained('packages');
            $table->text('business_data')->nullable();
            $table->text('project_objective')->nullable();
            $table->text('desired_features')->nullable();
            $table->text('references')->nullable();
            $table->string('desired_deadline')->nullable();
            $table->string('status')->default('received');
            $table->timestamps();
        });

        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users');
            $table->text('scope');
            $table->decimal('proposed_value', 10, 2);
            $table->integer('proposed_deadline_days');
            $table->json('includes')->nullable();
            $table->json('excludes')->nullable();
            $table->string('status')->default('pending'); // pending, accepted, rejected, revised
            $table->timestamps();
        });

        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('proposal_id')->nullable()->constrained();
            $table->text('terms');
            $table->integer('version')->default(1);
            $table->timestamps();
        });

        Schema::create('contract_acceptances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained(); // Client who accepted
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('accepted_at');
            $table->timestamps();
        });

        // === PROJECT MEMBERS & INVITATIONS ===
        Schema::create('project_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained(); // Employee
            $table->string('role_in_project');
            $table->decimal('commission_percent', 5, 2)->nullable();
            $table->decimal('commission_fixed', 10, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('project_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained(); // Invited Employee
            $table->foreignId('invited_by')->constrained('users');
            $table->string('proposed_role');
            $table->string('status')->default('pending'); // pending, accepted, rejected
            $table->timestamps();
        });

        // === GUILDA BOARD ===
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('board_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_column_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type')->default('task'); // task, bug, feature
            $table->string('priority')->default('medium'); // low, medium, high, critical
            $table->string('status')->default('normal');
            $table->integer('position')->default(0);
            $table->decimal('estimated_hours', 5, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('card_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });

        Schema::create('card_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->text('content');
            $table->timestamps();
        });

        // === SUPPORT EXTENSIONS ===
        Schema::create('support_ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->text('message');
            $table->timestamps();
        });

        // === FINANCIAL ===
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained();
            $table->foreignId('client_id')->constrained('users');
            $table->decimal('amount', 10, 2);
            $table->string('type'); // setup, subscription, extra
            $table->string('status')->default('pending'); // pending, paid, failed
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamps();
        });

        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained();
            $table->foreignId('user_id')->constrained(); // Employee
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending, paid
            $table->timestamps();
        });

        Schema::create('monthly_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained();
            $table->foreignId('client_id')->constrained('users');
            $table->decimal('amount', 10, 2)->default(150.00);
            $table->string('status')->default('active'); // active, inactive, past_due
            $table->timestamp('next_due_date')->nullable();
            $table->timestamps();
        });

        // === RANKING & REWARDS ===
        Schema::create('reward_events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('type'); // goal, ranking
            $table->decimal('bonus_amount', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('reward_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('reward_event_id')->constrained();
            $table->integer('points')->default(0);
            $table->timestamps();
        });

        // === REVIEWS ===
        Schema::create('project_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained();
            $table->foreignId('client_id')->constrained('users');
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        // === INCIDENTS ===
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('severity'); // low, medium, high, critical
            $table->string('status')->default('open'); // open, investigating, resolved
            $table->timestamps();
        });

        // === SYSTEM ===
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('action');
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('incidents');
        Schema::dropIfExists('project_reviews');
        Schema::dropIfExists('reward_scores');
        Schema::dropIfExists('reward_events');
        Schema::dropIfExists('monthly_subscriptions');
        Schema::dropIfExists('commissions');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('support_ticket_messages');
        Schema::dropIfExists('card_comments');
        Schema::dropIfExists('card_members');
        Schema::dropIfExists('cards');
        Schema::dropIfExists('board_columns');
        Schema::dropIfExists('boards');
        Schema::dropIfExists('project_invitations');
        Schema::dropIfExists('project_members');
        Schema::dropIfExists('contract_acceptances');
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('proposals');
        Schema::dropIfExists('project_requests');
        Schema::dropIfExists('employee_skills');
        Schema::dropIfExists('skills');
    }
};

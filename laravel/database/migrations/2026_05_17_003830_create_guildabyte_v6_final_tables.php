<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // === RBAC (Roles & Permissions) ===
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });
        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
        });

        // === Client Businesses ===
        Schema::create('client_businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('cnpj')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        // === Leads & Campaigns ===
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();
        });
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('content');
            $table->string('type'); // email, whatsapp
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();
        });
        Schema::create('campaign_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained(); // or lead_id
            $table->string('status')->default('pending');
        });

        // === Scope Addendums ===
        Schema::create('scope_addendums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->text('description');
            $table->decimal('additional_cost', 10, 2)->default(0);
            $table->integer('additional_days')->default(0);
            $table->boolean('accepted')->default(false);
            $table->timestamps();
        });

        // === Board Extra Tables ===
        Schema::create('card_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('card_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_checklist_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
        Schema::create('card_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->timestamps();
        });
        Schema::create('card_labels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color');
            $table->timestamps();
        });
        Schema::create('card_label_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->cascadeOnDelete();
            $table->foreignId('card_label_id')->constrained()->cascadeOnDelete();
        });
        Schema::create('card_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->cascadeOnDelete();
            $table->foreignId('depends_on_card_id')->constrained('cards')->cascadeOnDelete();
        });
        Schema::create('card_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('action');
            $table->timestamps();
        });

        // === Board Templates ===
        Schema::create('board_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('board_template_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_template_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('position');
            $table->timestamps();
        });
        Schema::create('board_template_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_template_column_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->integer('position');
            $table->timestamps();
        });

        // === Support Extensions ===
        Schema::create('support_ticket_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->timestamps();
        });
        Schema::create('maintenance_monthly_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->string('month_year'); // e.g. 05/2026
            $table->integer('requests_used')->default(0);
            $table->timestamps();
        });
        Schema::create('feature_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->text('description');
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        // === Rewards Extensions ===
        Schema::create('reward_event_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reward_event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
        });
        Schema::create('reward_winners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reward_event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount_awarded', 10, 2);
            $table->timestamps();
        });

        // === Reviews ===
        Schema::create('support_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained()->cascadeOnDelete();
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
        Schema::create('internal_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('evaluator_id')->constrained('users');
            $table->foreignId('evaluated_id')->constrained('users');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        // === Chat & Meetings ===
        Schema::create('project_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_chat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->text('message');
            $table->timestamps();
        });
        Schema::create('message_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_message_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->timestamps();
        });
        Schema::create('project_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('room_link');
            $table->timestamps();
        });
        Schema::create('room_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('joined_at');
        });
        Schema::create('meeting_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_room_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();
        });
        Schema::create('meeting_action_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_note_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });

        // === Files & Versions ===
        Schema::create('project_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->string('file_path');
            $table->string('file_name');
            $table->timestamps();
        });
        Schema::create('project_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('version_number');
            $table->text('changelog')->nullable();
            $table->timestamps();
        });

        // === Privacy (LGPD) ===
        Schema::create('privacy_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('consent_type'); // terms, privacy, marketing_email, cookies
            $table->string('ip_address')->nullable();
            $table->timestamp('consented_at');
        });
        Schema::create('data_subject_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('request_type'); // access, correction, deletion
            $table->string('status')->default('pending');
            $table->timestamps();
        });
        Schema::create('data_processing_records', function (Blueprint $table) {
            $table->id();
            $table->string('activity_name');
            $table->text('description');
            $table->timestamps();
        });
        Schema::create('communication_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('email_notifications')->default(true);
            $table->boolean('whatsapp_notifications')->default(true);
            $table->timestamps();
        });

        // === Additional Logs ===
        Schema::create('login_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->boolean('successful')->default(true);
            $table->timestamps();
        });
        Schema::create('security_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('event_type'); // failed_login, password_reset, role_changed
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('security_events');
        Schema::dropIfExists('login_logs');
        Schema::dropIfExists('communication_preferences');
        Schema::dropIfExists('data_processing_records');
        Schema::dropIfExists('data_subject_requests');
        Schema::dropIfExists('privacy_consents');
        Schema::dropIfExists('project_versions');
        Schema::dropIfExists('project_files');
        Schema::dropIfExists('meeting_action_items');
        Schema::dropIfExists('meeting_notes');
        Schema::dropIfExists('room_participants');
        Schema::dropIfExists('project_rooms');
        Schema::dropIfExists('message_attachments');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('project_chats');
        Schema::dropIfExists('internal_reviews');
        Schema::dropIfExists('support_reviews');
        Schema::dropIfExists('reward_winners');
        Schema::dropIfExists('reward_event_packages');
        Schema::dropIfExists('feature_requests');
        Schema::dropIfExists('maintenance_monthly_usage');
        Schema::dropIfExists('support_ticket_attachments');
        Schema::dropIfExists('board_template_cards');
        Schema::dropIfExists('board_template_columns');
        Schema::dropIfExists('board_templates');
        Schema::dropIfExists('card_activity_logs');
        Schema::dropIfExists('card_dependencies');
        Schema::dropIfExists('card_label_links');
        Schema::dropIfExists('card_labels');
        Schema::dropIfExists('card_attachments');
        Schema::dropIfExists('card_checklist_items');
        Schema::dropIfExists('card_checklists');
        Schema::dropIfExists('scope_addendums');
        Schema::dropIfExists('campaign_recipients');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('client_businesses');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};

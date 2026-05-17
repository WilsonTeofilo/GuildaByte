<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->string('business_type')->nullable();
            $table->string('business_name')->nullable();
            $table->string('niche')->nullable();
            $table->string('instagram')->nullable();
            $table->string('website')->nullable();
            $table->boolean('marketing_email')->default(false);
            $table->boolean('marketing_whatsapp')->default(false);
            $table->timestamps();
        });

        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role_title')->nullable();
            $table->string('seniority')->nullable();
            $table->text('bio')->nullable();
            $table->integer('project_limit')->default(3);
            $table->string('availability_status')->default('available'); // available, occupied, unavailable, suspended
            $table->timestamps();
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('package_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->integer('version_number');
            $table->decimal('base_price', 10, 2);
            $table->integer('setup_deadline_days');
            $table->text('description')->nullable();
            $table->json('included_items')->nullable();
            $table->json('excluded_items')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('discount_type'); // percent, fixed_value, final_price
            $table->decimal('discount_value', 10, 2);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('promotion_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->foreignId('package_version_id')->nullable()->constrained('package_versions')->nullOnDelete();
            $table->foreignId('promotion_id')->nullable()->constrained('promotions')->nullOnDelete();
            
            // Financial Snapshot (The most important rule of V6)
            $table->string('agreed_package_name');
            $table->integer('agreed_package_version');
            $table->decimal('agreed_base_value', 10, 2);
            $table->decimal('agreed_discount_value', 10, 2)->default(0);
            $table->decimal('agreed_final_value', 10, 2);
            $table->decimal('guildabyte_fee_percent', 5, 2)->default(20.00);
            $table->decimal('guildabyte_fee_value', 10, 2);
            $table->decimal('team_net_value', 10, 2);
            
            $table->integer('agreed_deadline_days')->nullable();
            
            // Statuses
            $table->string('status')->default('received'); // received, in_analysis, proposal_sent, awaiting_approval, in_development, delivered, maintenance
            $table->string('financial_status')->default('pending'); // pending, paid, late, cancelled
            
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('financial_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('event_type'); // price_created, manual_adjustment, discount_applied, payment_received
            $table->decimal('old_value', 10, 2)->nullable();
            $table->decimal('new_value', 10, 2)->nullable();
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->string('type'); // bug, maintenance, new_feature, critical_incident
            $table->string('category');
            $table->string('title');
            $table->text('description');
            $table->boolean('is_included')->default(false);
            $table->string('status')->default('open'); // open, triage, in_progress, waiting_client, resolved, closed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('financial_events');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('promotion_packages');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('package_versions');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('employee_profiles');
        Schema::dropIfExists('client_profiles');
    }
};

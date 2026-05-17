<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('call_slot_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type'); // 'status_alignment', 'delivery_review', 'urgency'
            $table->string('status')->default('pending'); // pending, confirmed, active, completed, cancelled, rejected
            $table->dateTime('scheduled_at');
            $table->text('agenda')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('admin_notes')->nullable();
            $table->json('checklist')->nullable();
            $table->string('meet_link')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_calls');
    }
};

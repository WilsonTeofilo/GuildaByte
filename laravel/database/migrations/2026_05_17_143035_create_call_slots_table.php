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
        Schema::create('call_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->cascadeOnDelete();
            $table->dateTime('start_time');
            $table->boolean('is_booked')->default(false);
            $table->timestamps();

            $table->index(['start_time', 'is_booked']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('call_slots');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique()->after('password');
            $table->string('auth_provider')->default('local')->after('google_id')->comment('local, google');
            
            // Note: Since only clients can register via Google Auth, 
            // the logic will enforce user_type='client' when auth_provider='google' on registration.
            // Employees/Admins must be created internally by Admin Root.
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'auth_provider']);
        });
    }
};

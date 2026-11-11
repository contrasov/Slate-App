<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('account_type')->default('professional');
            $table->string('public_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('specialty')->nullable();
            $table->string('timezone')->default('America/Fortaleza');
            $table->boolean('onboarding_complete')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'account_type', 'public_name', 'phone', 'specialty', 'timezone', 'onboarding_complete',
            ]);
        });
    }
};

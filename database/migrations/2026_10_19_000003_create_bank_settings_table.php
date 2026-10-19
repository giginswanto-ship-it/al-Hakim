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
        Schema::create('bank_settings', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name', 100);
            $table->string('account_number', 64);
            $table->string('account_name', 150);
            $table->string('bank_code', 10)->default('451');
            $table->string('qris_nmid', 64);
            $table->string('whatsapp_number', 32);
            $table->text('address')->nullable();
            $table->string('active_period', 50)->default('Oktober 2026');
            $table->decimal('starting_balance', 15, 2)->default(47000000.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_settings');
    }
};

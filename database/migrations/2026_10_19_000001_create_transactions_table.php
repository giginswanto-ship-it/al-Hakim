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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique();
            $table->date('transaction_date');
            $table->string('date_formatted', 32);
            $table->string('description', 255);
            $table->enum('type', ['Penerimaan', 'Pengeluaran']);
            $table->string('allocation_category', 100);
            $table->decimal('amount', 15, 2);
            $table->string('receipt_number', 64);
            $table->string('pic_name', 100);
            $table->string('payment_method', 64)->default('Kas Tunai');
            $table->text('audit_notes')->nullable();
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

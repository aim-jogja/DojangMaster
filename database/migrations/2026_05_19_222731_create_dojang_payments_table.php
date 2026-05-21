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
        Schema::create('dojang_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('dojang_id')
                ->constrained('dojangs')
                ->cascadeOnDelete();

            $table->date('period_start');
            $table->date('period_end');

            $table->decimal('amount', 12, 2);
            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'expired',
            ])->default('pending');

            $table->string('payment_method')->nullable();
            $table->string('invoice_number')->unique()->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dojang_payments');
    }
};

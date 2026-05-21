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
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_bill_id')
                ->constrained('student_bills')
                ->cascadeOnDelete();
        
            $table->foreignId('dojang_id')
                ->constrained('dojangs')
                ->cascadeOnDelete();
        
            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();
        
            $table->decimal('amount', 12, 2);
        
            $table->enum('payment_method', [
                'cash',
                'transfer',
                'qris',
                'other',
            ])->default('cash');
        
            $table->enum('status', [
                'paid',
                'cancelled',
                'refunded',
            ])->default('paid');
        
            $table->timestamp('paid_at')->nullable();
            $table->text('note')->nullable();
        
            $table->foreignId('received_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        
            $table->timestamps();
        
            $table->index(['dojang_id', 'student_id']);
            $table->index(['student_bill_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_payments');
    }
};

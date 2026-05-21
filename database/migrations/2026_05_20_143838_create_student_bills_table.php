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
        Schema::create('student_bills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('dojang_id')
                ->constrained('dojangs')
                ->cascadeOnDelete();
        
            $table->foreignId('room_id')
                ->nullable()
                ->constrained('rooms')
                ->nullOnDelete();
        
            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();
        
            $table->foreignId('payment_category_id')
                ->nullable()
                ->constrained('payment_categories')
                ->nullOnDelete();
        
            $table->string('invoice_number')->unique();
        
            $table->string('title');
            $table->text('description')->nullable();
        
            // contoh: 2026-05 untuk iuran bulanan
            $table->string('period')->nullable();
        
            $table->decimal('amount', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('remaining_amount', 12, 2)->default(0);
        
            $table->enum('status', [
                'unpaid',
                'partial',
                'paid',
                'cancelled',
                'expired',
            ])->default('unpaid');
        
            $table->date('due_date')->nullable();
        
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        
            $table->timestamps();
        
            $table->index(['dojang_id', 'room_id', 'student_id']);
            $table->index(['period', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_bills');
    }
};

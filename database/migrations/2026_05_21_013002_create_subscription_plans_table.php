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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique(); 
            // free, starter, pro, premium
        
            $table->string('name');
            $table->text('description')->nullable();
        
            $table->decimal('price', 12, 2)->default(0);
        
            // batas resource
            $table->integer('max_trainers')->nullable();
            $table->integer('max_students')->nullable();
            $table->integer('max_rooms')->nullable();
        
            $table->boolean('is_active')->default(true);
        
            $table->timestamps();
        
            $table->index(['code', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};

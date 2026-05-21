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
        Schema::create('dojang_subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('dojang_id')
                ->constrained('dojangs')
                ->cascadeOnDelete();

            $table->foreignId('subscription_plan_id')
                ->constrained('subscription_plans')
                ->restrictOnDelete();

            $table->date('started_at')->nullable();
            $table->date('expired_at')->nullable();

            $table->enum('status', [
                'active',
                'free',
                'overdue',
                'cancelled',
                'expired',
            ])->default('free');

            $table->timestamps();

            $table->unique('dojang_id');

            $table->index(['subscription_plan_id', 'status']);
            $table->index(['expired_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dojang_subscriptions');
    }
};

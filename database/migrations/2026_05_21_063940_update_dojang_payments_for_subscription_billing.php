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
        Schema::table('dojang_payments', function (Blueprint $table) {
            $table->foreignId('subscription_plan_id')
                ->nullable()
                ->after('dojang_id')
                ->constrained('subscription_plans')
                ->nullOnDelete();

            $table->date('due_date')
                ->nullable()
                ->after('period_end');

            $table->date('overdue_at')
                ->nullable()
                ->after('due_date');

            $table->text('note')
                ->nullable()
                ->after('paid_at');
        });

        DB::statement("ALTER TABLE dojang_payments MODIFY status ENUM('pending', 'paid', 'overdue', 'expired', 'failed', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dojang_payments', function (Blueprint $table) {
            $table->dropForeign(['subscription_plan_id']);
            $table->dropColumn([
                'subscription_plan_id',
                'due_date',
                'overdue_at',
                'note',
            ]);
        });

        DB::statement("ALTER TABLE dojang_payments MODIFY status ENUM('pending', 'paid', 'failed', 'expired') DEFAULT 'pending'");
    }
};

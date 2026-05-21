<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DojangPayment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'dojang_id',
        'subscription_plan_id',
        'period_start',
        'period_end',
        'due_date',
        'overdue_at',
        'amount',
        'status',
        'payment_method',
        'invoice_number',
        'paid_at',
        'note',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'due_date' => 'date',
        'overdue_at' => 'date',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function dojang()
    {
        return $this->belongsTo(Dojang::class, 'dojang_id');
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isOverdue(): bool
    {
        return $this->status === 'overdue';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }
}

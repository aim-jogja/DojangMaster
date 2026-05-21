<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DojangSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'dojang_id',
        'subscription_plan_id',
        'started_at',
        'expired_at',
        'status',
    ];

    protected $casts = [
        'started_at' => 'date',
        'expired_at' => 'date',
    ];

    public function dojang()
    {
        return $this->belongsTo(Dojang::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isFree(): bool
    {
        return $this->status === 'free'
            || $this->plan?->code === 'free';
    }

    public function isExpired(): bool
    {
        return $this->expired_at !== null
            && $this->expired_at->isPast();
    }

    public function isOverdue(): bool
    {
        return $this->status === 'overdue';
    }
}

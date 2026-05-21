<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'price',
        'max_trainers',
        'max_students',
        'max_rooms',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'max_trainers' => 'integer',
        'max_students' => 'integer',
        'max_rooms' => 'integer',
        'is_active' => 'boolean',
    ];

    public function features()
    {
        return $this->belongsToMany(
            Feature::class,
            'plan_features',
            'subscription_plan_id',
            'feature_id'
        )->withTimestamps();
    }

    public function isFree(): bool
    {
        return $this->code === 'free';
    }

    public function isPaid(): bool
    {
        return ! $this->isFree();
    }

    public function hasFeature(string $featureCode): bool
    {
        return $this->features()
            ->where('features.code', $featureCode)
            ->where('features.is_active', true)
            ->exists();
    }

    public function subscriptions()
    {
        return $this->hasMany(DojangSubscription::class);
    }

    public function payments()
    {
        return $this->hasMany(DojangPayment::class);
    }
}

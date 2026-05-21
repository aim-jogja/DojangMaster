<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function plans()
    {
        return $this->belongsToMany(
            SubscriptionPlan::class,
            'plan_features',
            'feature_id',
            'subscription_plan_id'
        )->withTimestamps();
    }
}

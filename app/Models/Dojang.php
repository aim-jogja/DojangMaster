<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dojang extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'domain',
        'phone',
        'is_active'
    ];

    public function owner(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function trainers(){
        return $this->hasMany(User::class, 'dojang_id')->whereHas('roles', function ($query) {
            $query->where('name', 'trainer');
        });;
    }

    public function users()
    {
        return $this->hasMany(User::class, 'dojang_id')->whereHas('roles', function ($query) {
            $query->where('name', 'student');
        });;
    }

    public function rooms(){
        return $this->hasMany(Room::class, 'dojang_id');
    }

    public function paymentCategories()
    {
        return $this->hasMany(PaymentCategory::class);
    }

    public function studentBills()
    {
        return $this->hasMany(StudentBill::class);
    }

    public function studentPayments()
    {
        return $this->hasMany(StudentPayment::class);
    }

    public function dojangPayments()
    {
        return $this->hasMany(DojangPayment::class);
    }

    public function subscription()
    {
        return $this->hasOne(DojangSubscription::class);
    }

    public function currentPlan()
    {
        return $this->subscription?->plan;
    }

    public function canUseFeature(string $featureCode): bool
    {
        $plan = $this->currentPlan();

        if (! $plan || ! $plan->is_active) {
            return false;
        }

        return $plan->hasFeature($featureCode);
    }

    public function hasReachedRoomLimit(): bool
    {
        $plan = $this->currentPlan();

        if (! $plan || is_null($plan->max_rooms)) {
            return false;
        }

        return $this->rooms()->count() >= $plan->max_rooms;
    }

    public function hasReachedStudentLimit(): bool
    {
        $plan = $this->currentPlan();

        if (! $plan || is_null($plan->max_students)) {
            return false;
        }

        return $this->users()->count() >= $plan->max_students;
    }

    public function hasReachedTrainerLimit(): bool
    {
        $plan = $this->currentPlan();

        if (! $plan || is_null($plan->max_trainers)) {
            return false;
        }

        return $this->trainers()->count() >= $plan->max_trainers;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'dojang_id',
        'name',
        'description',
        'registration_fee',
        'monthly_fee',
        'is_active'
    ];

    public function dojang()
    {
        return $this->belongsTo(Dojang::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'room_user', 'room_id', 'user_id')
            ->withPivot([
                'joined_at',
                'is_active',
            ])
            ->withTimestamps();
    }

    public function studentBills()
    {
        return $this->hasMany(StudentBill::class);
    }
}
  
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
}

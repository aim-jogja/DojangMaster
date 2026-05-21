<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'dojang_id',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function dojang()
    {
        return $this->belongsTo(Dojang::class);
    }

    public function bills()
    {
        return $this->hasMany(StudentBill::class);
    }
}

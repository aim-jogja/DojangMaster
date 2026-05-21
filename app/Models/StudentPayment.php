<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_bill_id',
        'dojang_id',
        'student_id',
        'amount',
        'payment_method',
        'status',
        'paid_at',
        'note',
        'received_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function bill()
    {
        return $this->belongsTo(StudentBill::class, 'student_bill_id');
    }

    public function dojang()
    {
        return $this->belongsTo(Dojang::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}

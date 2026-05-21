<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'dojang_id',
        'room_id',
        'student_id',
        'payment_category_id',
        'invoice_number',
        'title',
        'description',
        'period',
        'amount',
        'paid_amount',
        'remaining_amount',
        'status',
        'due_date',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function dojang()
    {
        return $this->belongsTo(Dojang::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function category()
    {
        return $this->belongsTo(PaymentCategory::class, 'payment_category_id');
    }

    public function payments()
    {
        return $this->hasMany(StudentPayment::class, 'student_bill_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function recalculatePaymentStatus(): void
    {
        $paidAmount = $this->payments()
            ->where('status', 'paid')
            ->sum('amount');

        $remainingAmount = max($this->amount - $paidAmount, 0);

        $status = match (true) {
            $paidAmount <= 0 => 'unpaid',
            $paidAmount < $this->amount => 'partial',
            default => 'paid',
        };

        $this->update([
            'paid_amount' => $paidAmount,
            'remaining_amount' => $remainingAmount,
            'status' => $status,
        ]);
    }
}

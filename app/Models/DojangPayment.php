<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DojangPayment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'dojang_id',
        'period_start',
        'period_end',
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
        'paid_at' => 'datetime',
    ];

    public function dojang()
    {
        return $this->belongsTo(Dojang::class, 'dojang_id');
    }
}

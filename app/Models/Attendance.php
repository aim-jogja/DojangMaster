<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'user_id',
        'date',
        'status',
        'note',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

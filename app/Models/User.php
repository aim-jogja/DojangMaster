<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dojang_id',
        'name',
        'email',
        'password',
        'qr_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function ownedDojangs()
    {
        return $this->hasMany(Dojang::class, 'user_id');
    }

    public function dojang(){
        return $this->belongsTo(Dojang::class, 'dojang_id');
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_user', 'user_id', 'room_id')
            ->withPivot([
                'joined_at',
                'is_active',
            ])
            ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    public function studentBills()
    {
        return $this->hasMany(StudentBill::class, 'student_id');
    }

    public function studentPayments()
    {
        return $this->hasMany(StudentPayment::class, 'student_id');
    }

    public function receivedStudentPayments()
    {
        return $this->hasMany(StudentPayment::class, 'received_by');
    }
}

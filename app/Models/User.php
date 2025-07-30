<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'mobile_number',
        'password',
        'first_name',
        'last_name',
        'address',
        'balance',
        'referral_code',
        'referred_by_id',
        'level_id',
        'kyc_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // User ka level
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    // User ko kisne refer kia
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by_id');
    }

    // User ne kis kis ko refer kia (Direct referrals)
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by_id');
    }

    // User ki saari transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

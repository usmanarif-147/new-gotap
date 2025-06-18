<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'company_name',
        'enterprise_logo',
        'photo',
        'role',
        'status',
        'is_suspended',
        'password',
        'address',
        'gender',
        'dob',
        'tiks',
        'token',
        'verified',
        'featured',
        'fcm_token',
        'deactivated_at',
        'is_email_sent',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    public function userSubscription()
    {
        return $this->hasOne(UserSubscription::class, 'enterprise_id');
    }
}

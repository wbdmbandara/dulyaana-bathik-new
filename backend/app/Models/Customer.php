<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasApiTokens;
    
    protected $table = 'customer';
    protected $fillable = [
        'name',
        'email',
        'email_confirmed',
        'phone',
        'phone_confirmed',
        'birthday',
        'gender',
        'password',
        'subscribed_to_newsletter',
        'last_login',
        'pw_reset_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
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
    ];
}

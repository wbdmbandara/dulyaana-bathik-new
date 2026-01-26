<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $table = 'customer_address';
    protected $fillable = [
        'full_name',
        'phone_number',
        'customer_id',
        'address_line1',
        'address_line2',
        'type',
        'city',
        'state',
        'postal_code',
        'label',
        'is_default',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShipping extends Model
{
    protected $table = 'order_shippings';

    protected $fillable = [
        'order_id',
        'courier_name',
        'courier_tracking_no',
        'tracking_number',
        'full_name',
        'phone_number',
        'customer_id',
        'address_line1',
        'address_line2',
        'type',
        'city',
        'state',
        'postal_code',
    ];
}

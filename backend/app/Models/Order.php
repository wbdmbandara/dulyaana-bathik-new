<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'order_date',
        'order_time',
        'items_count',
        'total_quantity',
        'total_amount',
        'shipping_fee',
        'discount',
        'final_amount',
        'payment_method',
        'note',
        'status',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';

    protected $fillable = [
        'customer_id',
        'item_id',
        'quantity',
        'added_at',
    ];

}

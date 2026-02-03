<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    protected $table = 'payment_statuses';

    protected $fillable = [
        'order_id',
        'payment_status',
        'changed_user_id',
    ];
}

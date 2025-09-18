<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    protected $table = 'items';
    protected $fillable = [
        'name',
        'description',
        'price',
        'discount_price',
        'quantity',
        'url',
        'fabric',
        'pattern',
        'saree_work',
        'saree_length',
        'blouse_length',
        'set_contents',
        'weight',
        'occasion',
        'wash_care',
        'status',
        'category',
        'main_image',
        'added_by'
    ];
}

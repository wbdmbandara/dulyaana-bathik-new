<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCards extends Model
{
    protected $table = 'promo_cards';
    protected $fillable = [
        'small_text',
        'promo_title',
        'promo_description',
        'promo_link',
        'button_text',
        'promo_image'
    ];
}

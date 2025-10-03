<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterMenus extends Model
{
    protected $table = 'footer_menus';
    protected $fillable = [
        'title',
        'url',
        'menu_type',
        'user_id',
    ];
}

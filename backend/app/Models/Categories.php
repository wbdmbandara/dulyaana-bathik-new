<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'item_category';

    protected $fillable = [
        'cat_name',
        'cat_description',
        'cat_slug',
        'parent_id',
    ];
}

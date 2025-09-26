<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SliderImages extends Model
{
    protected $table = 'slider_images';
    protected $fillable = ['image_path', 'alt_text', 'order', 'visibility'];
}

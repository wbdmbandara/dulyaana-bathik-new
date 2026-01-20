<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeSettings extends Model
{
    protected $table = 'theme_settings';

    protected $fillable = [
        'background_color',
        'default_color',
        'heading_color',
        'accent_color',
        'surface_color',
        'contrast_color',
        'nav_color',
        'nav_hover_color',
        'nav_mobile_background_color',
        'nav_dropdown_background_color',
        'nav_dropdown_color',
        'nav_dropdown_hover_color',
    ];
}

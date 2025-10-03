<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterContents extends Model
{
    protected $table = 'footer_contents';
    protected $fillable = [
        'site_name',
        'about_text',
        'play_store_link',
        'app_store_link',
        'address',
        'email01',
        'email02',
        'phone01',
        'phone02',
        'open_time',
        'user_id',
    ];
}

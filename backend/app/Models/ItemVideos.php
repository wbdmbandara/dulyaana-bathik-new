<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemVideos extends Model
{
    protected $table = 'item_videos';
    protected $fillable = [
        'item_id',
        'video_url',
        'video_type',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    protected $fillable = [
        'image',
        'name',
        'slug',
        'desc',
        'content',
        'meta_title',
        'meta_desc',
        'meta_image',
    ];
}

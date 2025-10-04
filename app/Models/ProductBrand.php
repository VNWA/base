<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'url_avatar',
        'name',
        'slug',
        'description',
        'content',
        'meta_image',
        'meta_title',
        'meta_desc',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'product_brand_id');
    }
}

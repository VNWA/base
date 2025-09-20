<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_brand_id',
        'url_avatar',
        'images',
        'sku',
        'stock',
        'origin',
        'name',
        'slug',
        'description',
        'append',
        'meta_image',
        'meta_title',
        'meta_desc',
    ];

    protected $casts = [
        'images' => 'array',
        'append' => 'array',
    ];

    public function brand()
    {
        return $this->belongsTo(ProductBrand::class, 'product_brand_id');
    }

    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_and_category_pivot');
    }
}

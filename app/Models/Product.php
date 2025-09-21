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
        'price',
        'price_old',
        'meta_image',
        'meta_title',
        'meta_desc',
    ];

    protected $casts = [
        'images' => 'array',
        'append' => 'array',
        'price' => 'decimal:0',
        'price_old' => 'decimal:0',
    ];
    public function getDiscountPercentAttribute()
    {
        if ($this->price_old && $this->price_old > $this->price) {
            return round((($this->price_old - $this->price) / $this->price_old) * 100);
        }
        return 0;
    }
    public function quantityDiscounts()
    {
        return $this->hasMany(ProductQtyDiscount::class);
    }

    public function getPriceByQuantity($qty)
    {
        $discount = $this->quantityDiscounts()
            ->where('min_qty', '<=', $qty)
            ->orderByDesc('min_qty')
            ->first();

        return $discount ? $discount->price : $this->price;
    }
    public function brand()
    {
        return $this->belongsTo(ProductBrand::class, 'product_brand_id');
    }

    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_and_category_pivot');
    }
}

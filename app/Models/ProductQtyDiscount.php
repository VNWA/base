<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductQtyDiscount extends Model
{
    protected $fillable = ['product_id', 'min_qty', 'price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

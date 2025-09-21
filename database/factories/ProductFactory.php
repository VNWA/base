<?php

namespace Database\Factories;

use App\Models\ProductBrand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition()
    {
        $name = $this->faker->words(3, true);

        // Giá hiện tại và giá cũ
        $price = $this->faker->randomFloat(2, 50, 500); // giá từ 50 -> 500
        $priceOld = $this->faker->boolean(50) ? $price + $this->faker->numberBetween(10, 200) : null;

        return [
            'product_brand_id' => ProductBrand::factory(),
            'url_avatar'       => $this->faker->imageUrl(200, 200, 'product'),
            'images'           => json_encode([
                $this->faker->imageUrl(600, 600, 'product'),
                $this->faker->imageUrl(600, 600, 'product'),
            ]),
            'sku'              => strtoupper($this->faker->bothify('SKU-#####')),
            'stock'            => $this->faker->numberBetween(0, 500),
            'origin'           => $this->faker->country,
            'name'             => $name,
            'slug'             => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 10000),
            'description'      => $this->faker->paragraph,
            'append'           => [],

            'price'            => $price,
            'price_old'        => $priceOld,

            'meta_image'       => $this->faker->imageUrl(),
            'meta_title'       => $this->faker->sentence,
            'meta_desc'        => $this->faker->paragraph,
        ];
    }
}

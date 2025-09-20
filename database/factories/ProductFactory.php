<?php

namespace Database\Factories;

use App\Models\ProductBrand;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition()
    {
        $name =  $this->faker->words(3, true);

        return [
            'product_brand_id' => ProductBrand::factory(),
            'url_avatar' => $this->faker->imageUrl(200, 200, 'product'),
            'images' => [$this->faker->imageUrl(), $this->faker->imageUrl()],
            'sku' => strtoupper($this->faker->bothify('SKU-#####')),
            'stock' => $this->faker->numberBetween(0, 500),
            'origin' => $this->faker->country,
            'name' => $name,
            'slug' => \Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 10000),
            'description' => $this->faker->paragraph,
            'append' => ['color' => $this->faker->safeColorName, 'size' => $this->faker->word],
            'meta_image' => $this->faker->imageUrl(),
            'meta_title' => $this->faker->sentence,
            'meta_desc' => $this->faker->paragraph,
        ];
    }
}

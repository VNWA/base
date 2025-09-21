<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductCategoryFactory extends Factory
{
    public function definition()
    {
        $name = $this->faker->unique()->words(2, true); // ví dụ: "Smart Phones"

        return [
            'parent_id' => null,
            'ord' => $this->faker->numberBetween(0, 10),
            'url_avatar' => $this->faker->imageUrl(200, 200, 'category'),
            'name' => $name,
            'slug' => Str::slug($name).'-'.$this->faker->unique()->numberBetween(1, 100000),
            'description' => $this->faker->sentence(),
            'meta_image' => $this->faker->imageUrl(),
            'meta_title' => $this->faker->sentence(),
            'meta_desc' => $this->faker->paragraph(),
        ];
    }
}

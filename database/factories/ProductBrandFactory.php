<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductBrandFactory extends Factory
{
    public function definition()
    {
        $name = $this->faker->company;
        return [
            'url_avatar' => $this->faker->imageUrl(200, 200, 'business'),
            'name' => $name,
            'slug' => \Str::slug($name),
            'description' => $this->faker->sentence,
            'meta_image' => $this->faker->imageUrl(),
            'meta_title' => $this->faker->sentence,
            'meta_desc' => $this->faker->paragraph,
        ];
    }
}

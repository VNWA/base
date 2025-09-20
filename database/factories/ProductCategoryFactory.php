<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCategoryFactory extends Factory
{
    public function definition()
    {
        $name = $this->faker->word;
        return [
            'parent_id' => null,
            'ord' => $this->faker->numberBetween(0, 10),
            'url_avatar' => $this->faker->imageUrl(200, 200, 'category'),
            'name' => ucfirst($name),
            'slug' => \Str::slug($name),
            'description' => $this->faker->sentence,
            'meta_image' => $this->faker->imageUrl(),
            'meta_title' => $this->faker->sentence,
            'meta_desc' => $this->faker->paragraph,
        ];
    }
}

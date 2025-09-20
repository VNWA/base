<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductBrand;

class ProductBrandSeeder extends Seeder
{
    public function run(): void
    {
        ProductBrand::factory(5)->create();
    }
}

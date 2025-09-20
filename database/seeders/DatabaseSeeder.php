<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProductBrandSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            SettingSeeder::class
        ]);
        app()[PermissionRegistrar::class]->forgetCachedPermissions();



    }
}

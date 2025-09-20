<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleSuperAdmin = Role::create(['name' => 'super admin']);
        $rolleUser = Role::create(['name' => 'user']);

        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@vinawebapp.com',
            'password' => Hash::make('admin@123'),
        ]);
        $admin->ulid = Str::ulid()->toBase32();
        $admin->email_verified_at = now();
        $admin->save(['timestamps' => false]);

        $admin->assignRole($roleAdmin);
        $admin->assignRole($roleSuperAdmin);


        $user = User::factory()->create([
            'name' => 'user',
            'email' => 'user@vinawebapp.com',
            'password' => Hash::make('user@123'),
        ]);
        $user->ulid = Str::ulid()->toBase32();
        $user->email_verified_at = now();
        $user->save(['timestamps' => false]);

        $user->assignRole($rolleUser);


    }
}

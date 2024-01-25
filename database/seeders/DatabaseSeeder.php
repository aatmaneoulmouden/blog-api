<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create super admin
        \App\Models\User::factory()->create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'username' => 'useradmin',
            'email' => 'useradmin@email.com',
            'password' => Hash::make('Pa$$w0rd'),
            'super_admin' => true,
        ]);

        // Create regural user
        \App\Models\User::factory()->create([
            'first_name' => 'Regural',
            'last_name' => 'User',
            'username' => 'reguraluser',
            'email' => 'reguraluser@email.com',
            'password' => Hash::make('Pa$$w0rd'),
            'super_admin' => false,
        ]);

        // Seed database
        // \App\Models\User::factory(10)->create();
        // \App\Models\Category::factory(50)->create();
        // \App\Models\Tag::factory(100)->create();


        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}

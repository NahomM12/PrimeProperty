<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create an admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        Admin::create([
            'user_id' => $admin->id,
        ]);
        $this->call([
            UserAndAdminSeeder::class,
           
          PropertyTypeSeeder::class,
          PropertySeeder::class,
           PropertyDetailSeeder::class,
            TransactionSeeder::class,
           // 
        ]);
    }
}

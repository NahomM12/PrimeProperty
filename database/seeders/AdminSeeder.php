<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin; // Assuming Admin model exists

class AdminSeeder extends Seeder
{
    public function run()
    {
        // You can use this method to seed a single admin
        Admin::create([
            'name' => 'Abel Kassahun', 
            'email' => 'admin@gmail.com',
            'phone' => '1234567890',
            'role' => 'admin', // You can change this if needed
            'password' => Hash::make('adminpassword'), // Use a secure password
        ]);

       
    }
}
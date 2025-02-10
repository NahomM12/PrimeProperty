<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Manager; 
use App\Models\Region;
use App\Models\SubRegion;

class ManagerSeeder extends Seeder
{
    public function run()
    {
        // Get a sample region and sub_region (or you can create them as well)
        $region = Region::first(); // Assuming there's at least one region in the database
        $subRegion = SubRegion::first(); // Assuming there's at least one sub_region in the database

        // Create a manager with the relevant data
        Manager::create([
            'name' => 'Manager One',
            'email' => 'manager1@example.com',
            'phone' => '1234567890',
            'address' => '123 Main St, City, Country',
            'status' => 'active',
            'role' => 'manager',
            'password' => Hash::make('managerpassword'),
            'region_id' => $region->id, // Make sure the region exists
            'sub_region_id' => $subRegion->id, // Make sure the sub_region exists
        ]);

        // Optionally, you can add more managers
        Manager::create([
            'name' => 'Manager Two',
            'email' => 'manager2@example.com',
            'phone' => '0987654321',
            'address' => '456 Another St, City, Country',
            'status' => 'active',
            'role' => 'manager',
            'password' => Hash::make('managerpassword123'),
            'region_id' => $region->id,
            'sub_region_id' => $subRegion->id,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run()
    {
        Property::create([
            'title' => 'Luxury Downtown Apartment',
            'description' => 'Beautiful apartment in the heart of downtown',
            'address' => '789 Downtown Ave, City',
            'owner' => 1,
            'price' => 500000,
            'images' => ['images/Apartment1.jpg', 'images/Apartment2.jpg'],
            'status' => 'available',
            'property_use' => 'sale',
            'property_type_id' => 1, // Apartment
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'field_values' => [
                'year_built' => '2020',
                'parking' => 'Yes',
                'air_conditioning' => 'Central',
                'floor_size' => '1200 sqft'
            ]
        ]);

        Property::create([
            'title' => 'Modern Studio',
            'description' => 'Cozy studio perfect for singles or couples',
            'owner' => 1,
            'address' => '101 Studio Lane, City',
            'price' => 1500,
            'images' => ['images/Studio1.jpg', 'images/Studio2.jpg'],
            'status' => 'available',
            'property_use' => 'rent',
            'property_type_id' => 4, // Studio
            'latitude' => 40.7142,
            'longitude' => -74.0064,
            'field_values' => [
                'year_built' => '2019',
                'parking' => 'No',
                'air_conditioning' => 'Window Unit',
                'floor_size' => '500 sqft'
            ]
        ]);

        Property::create([
            'title' => 'Family House',
            'description' => 'Spacious family house with garden',
            'address' => '202 Family Road, Suburb',
            'owner' => 1,
            'price' => 750000,
            'images' => ['images/House1.jpg', 'images/House2.jpg', 'images/House3.jpg'],
            'status' => 'available',
            'property_use' => 'rent',
            'property_type_id' => 2, // House
            'latitude' => 40.7135,
            'longitude' => -74.0070,
            'field_values' => [
                'year_built' => '2018',
                'parking' => 'Garage',
                'air_conditioning' => 'Central',
                'floor_size' => '2500 sqft',
                'garden_size' => '1000 sqft'
            ]
        ]);
    }
}
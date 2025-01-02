<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Region;
use App\Models\Subregion;
use App\Models\Location;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create some sample regions, subregions, and locations
        $region1 = Region::create(['region_name' => 'Region 5']);
        $region2 = Region::create(['region_name' => 'Region 6']);
        $subregion1 = Subregion::create(['subregion_name' => 'Subregion 1', 'region_id' => $region1->id]);
        $subregion2 = Subregion::create(['subregion_name' => 'Subregion 2', 'region_id' => $region2->id]);
        $location1 = Location::create(['location' => 'Location 1', 'subregion_id' => $subregion1->id]);
        $location2 = Location::create(['location' => 'Location 2', 'subregion_id' => $subregion2->id]);

        // Create some sample properties
        $property1 = Property::create([
            'title' => 'Property 1',
            'description' => 'This is a sample property',
            'address' => '123 Main St, Anytown USA',
            'price' => 100000,
            'status' => 'available',
            'owner' => '1',
            'property_use' => 'sale',
            'property_type_id' => 1,
            'latitude' => 40.730610,
            'longitude' => -73.935242,
            'images' => 'https://via.placeholder.com/640x480.png/00dd00?text=property1',
            'field_values' => 'sample,field,values',
            'is_featured' => true,
            'region_id' => $region1->id,
            'subregion_id' => $subregion1->id,
            'location_id' => $location1->id,
           
        ]);

        $property2 = Property::create([
            'title' => 'Property 2',
            'description' => 'This is another sample property',
            'address' => '456 Oak Rd, Anytown USA',
            'price' => 50000,
            'status' => 'available',
            'owner' => '2',
            'property_use' => 'rent',
            'property_type_id' => 2,
            'latitude' => 41.850033,
            'longitude' => -87.6500523,
            'images' => 'https://via.placeholder.com/640x480.png/00dd00?text=property2',
            'field_values' => 'another,sample,field,values',
            'is_featured' => false,
            'region_id' => $region2->id,
            'subregion_id' => $subregion2->id,
            'location_id' => $location2->id,
            
        ]);
// Create some sample customers and sellers
$customer1 = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => bcrypt('password'),
    'role' => 'customer',
]);

$seller1 = User::create([
    'name' => 'Jane Smith',
    'email' => 'jane@example.com',
    'password' => bcrypt('password'),
    'role' => 'seller',
]);

// Create some sample transactions
$transaction1 = Transaction::create([
    'property_id' => $property1->id,
    'owner' => $seller1->id,
    'customer' => $customer1->id,
    'transaction_type' => $property1->property_use,
    'transaction_date' => now(),
    'price' => $property1->price,
    'commission' => 100,
]);

$transaction2 = Transaction::create([
    'property_id' => $property2->id,
    'owner' => $seller1->id,
    'customer' => $customer1->id,
    'transaction_type' => $property2->property_use,
    'transaction_date' => now(),
    'price' => $property2->price,
   'commission' => 50,
]);

// Output the transaction data in JSON format
$transactions = [
    'transaction1' => $transaction1->toArray(),
    'transaction2' => $transaction2->toArray(),
];

dump(json_encode($transactions, JSON_PRETTY_PRINT));


    }
}
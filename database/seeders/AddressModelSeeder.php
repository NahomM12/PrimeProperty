namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\SubRegion;
use App\Models\Location;

class AddressModelSeeder extends Seeder
{
    public function run()
    {
        // Seed Regions
        $regions = [
            ['region_name' => 'North America'],
            ['region_name' => 'Europe'],
            ['region_name' => 'Asia'],
        ];

        foreach ($regions as $regionData) {
            $region = Region::firstOrCreate(['region_name' => $regionData['region_name']]);

            // Seed SubRegions for each Region
            $subRegions = [
                ['subregion_name' => 'United States', 'region_id' => $region->id],
                ['subregion_name' => 'Canada', 'region_id' => $region->id],
                ['subregion_name' => 'Germany', 'region_id' => $region->id],
                ['subregion_name' => 'France', 'region_id' => $region->id],
                ['subregion_name' => 'China', 'region_id' => $region->id],
                ['subregion_name' => 'Japan', 'region_id' => $region->id],
            ];

            foreach ($subRegions as $subRegionData) {
                $subRegion = SubRegion::firstOrCreate(['subregion_name' => $subRegionData['subregion_name'], 'region_id' => $region->id]);

                // Seed Locations for each SubRegion
                $locations = [
                    ['location' => 'New York', 'region_id' => $region->id, 'subregion_id' => $subRegion->id],
                    ['location' => 'Los Angeles', 'region_id' => $region->id, 'subregion_id' => $subRegion->id],
                    ['location' => 'Toronto', 'region_id' => $region->id, 'subregion_id' => $subRegion->id],
                    ['location' => 'Berlin', 'region_id' => $region->id, 'subregion_id' => $subRegion->id],
                    ['location' => 'Paris', 'region_id' => $region->id, 'subregion_id' => $subRegion->id],
                    ['location' => 'Beijing', 'region_id' => $region->id, 'subregion_id' => $subRegion->id],
                    ['location' => 'Tokyo', 'region_id' => $region->id, 'subregion_id' => $subRegion->id],
                ];

                foreach ($locations as $locationData) {
                    Location::firstOrCreate($locationData);
                }
            }
        }
    }
}

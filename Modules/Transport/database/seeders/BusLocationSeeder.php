<?php


namespace Modules\Transport\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Transport\Entities\Bus;
use Modules\Transport\Entities\BusLocation;

class BusLocationSeeder extends Seeder
{
    public function run(): void
    {
        $bus1 = Bus::where('plate_number', 'SY-1001')->first();
        $bus2 = Bus::where('plate_number', 'SY-1002')->first();

        if (!$bus1 || !$bus2) {
            throw new \Exception('Buses not found. Run BusSeeder first.');
        }

        BusLocation::create([
            'bus_id' => $bus1->id,
            'latitude' => 33.5138000,
            'longitude' => 36.3125000,
            'speed' => 25.50,
            'heading' => 90.00,
            'accuracy' => 5.00,
            'recorded_at' => now()->subMinutes(5),
        ]);

        BusLocation::create([
            'bus_id' => $bus1->id,
            'latitude' => 33.5152000,
            'longitude' => 36.3140000,
            'speed' => 30.20,
            'heading' => 95.00,
            'accuracy' => 4.00,
            'recorded_at' => now()->subMinutes(2),
        ]);

        BusLocation::create([
            'bus_id' => $bus1->id,
            'latitude' => 33.5195000,
            'longitude' => 36.3137000,
            'speed' => 18.70,
            'heading' => 100.00,
            'accuracy' => 3.00,
            'recorded_at' => now(),
        ]);

        BusLocation::create([
            'bus_id' => $bus2->id,
            'latitude' => 33.4897000,
            'longitude' => 36.2919000,
            'speed' => 22.00,
            'heading' => 180.00,
            'accuracy' => 5.00,
            'recorded_at' => now(),
        ]);
    }
}

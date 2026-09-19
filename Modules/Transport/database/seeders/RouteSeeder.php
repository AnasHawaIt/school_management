<?php


namespace Modules\Transport\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Transport\app\Entities\Bus;
use Modules\Transport\app\Entities\Route;

class RouteSeeder extends Seeder
{
    public function run(): void
    {
        $bus1 = Bus::where('plate_number', 'SY-1001')->first();
        $bus2 = Bus::where('plate_number', 'SY-1002')->first();

        if (!$bus1 || !$bus2) {
            throw new \Exception('Buses not found. Run BusSeeder first.');
        }

        Route::firstOrCreate(
            [
                'bus_id' => $bus1->id,
                'name' => 'الخط الأول - دمشق',
            ]
        );

        Route::firstOrCreate(
            [
                'bus_id' => $bus2->id,
                'name' => 'الخط الثاني - دمشق',
            ]
        );
    }
}

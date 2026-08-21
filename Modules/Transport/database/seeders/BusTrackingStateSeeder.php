<?php


namespace Modules\Transport\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Transport\Entities\Bus;
use Modules\Transport\Entities\RouteStop;
use Modules\Transport\Entities\BusTrackingState;

class BusTrackingStateSeeder extends Seeder
{
    public function run(): void
    {
        $bus1 = Bus::where('plate_number', 'SY-1001')->first();
        $bus2 = Bus::where('plate_number', 'SY-1002')->first();

        $stop1 = RouteStop::where('stop_name', 'باب توما')->first();
        $stop2 = RouteStop::where('stop_name', 'الميدان')->first();

        if (!$bus1 || !$bus2) {
            throw new \Exception('Buses not found.');
        }

        if (!$stop1 || !$stop2) {
            throw new \Exception('Route stops not found.');
        }

        BusTrackingState::updateOrCreate(
            [
                'bus_id' => $bus1->id,
                'route_stop_id' => $stop1->id,
            ],
            [
                'stage' => 'approaching',
                'last_notified_at' => now()->subMinutes(3),
            ]
        );

        BusTrackingState::updateOrCreate(
            [
                'bus_id' => $bus2->id,
                'route_stop_id' => $stop2->id,
            ],
            [
                'stage' => 'near',
                'last_notified_at' => now()->subMinutes(1),
            ]
        );
    }
}

<?php


namespace Modules\Transport\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Transport\Entities\Bus;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        $buses = [
            [
                'plate_number' => 'SY-1001',
                'status' => 'active',
                'capacity' => 40,
            ],
            [
                'plate_number' => 'SY-1002',
                'status' => 'active',
                'capacity' => 35,
            ],
            [
                'plate_number' => 'SY-1003',
                'status' => 'maintenance',
                'capacity' => 45,
            ],
        ];

        foreach ($buses as $bus) {
            Bus::firstOrCreate(
                ['plate_number' => $bus['plate_number']],
                [
                    'status' => $bus['status'],
                    'capacity' => $bus['capacity'],
                ]
            );
        }
    }
}

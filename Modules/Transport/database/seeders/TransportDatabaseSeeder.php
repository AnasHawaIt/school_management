<?php

namespace Modules\Transport\database\seeders;

use Illuminate\Database\Seeder;

class TransportDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            BusSeeder::class,
            RouteSeeder::class,
            RouteStopSeeder::class,
            SubscriptionSeeder::class,
            BusLocationSeeder::class,
            BusTrackingStateSeeder::class,
        ]);
    }
}

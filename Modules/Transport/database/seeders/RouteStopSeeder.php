<?php


namespace Modules\Transport\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Transport\app\Entities\Route;
use Modules\Transport\app\Entities\RouteStop;

class RouteStopSeeder extends Seeder
{
    public function run(): void
    {
        $route1 = Route::where('name', 'الخط الأول - دمشق')->first();
        $route2 = Route::where('name', 'الخط الثاني - دمشق')->first();

        if (!$route1 || !$route2) {
            throw new \Exception('Routes not found. Run RouteSeeder first.');
        }

        $stops = [
            [
                'route_id' => $route1->id,
                'stop_name' => 'باب توما',
                'latitude' => 33.5138000,
                'longitude' => 36.3125000,
                'sequence' => 1,
                'estimated_arrival_time' => '07:15:00',
            ],
            [
                'route_id' => $route1->id,
                'stop_name' => 'العباسيين',
                'latitude' => 33.5195000,
                'longitude' => 36.3137000,
                'sequence' => 2,
                'estimated_arrival_time' => '07:25:00',
            ],
            [
                'route_id' => $route1->id,
                'stop_name' => 'المزة',
                'latitude' => 33.5009000,
                'longitude' => 36.2677000,
                'sequence' => 3,
                'estimated_arrival_time' => '07:40:00',
            ],
            [
                'route_id' => $route2->id,
                'stop_name' => 'الميدان',
                'latitude' => 33.4897000,
                'longitude' => 36.2919000,
                'sequence' => 1,
                'estimated_arrival_time' => '07:10:00',
            ],
            [
                'route_id' => $route2->id,
                'stop_name' => 'كفرسوسة',
                'latitude' => 33.4833000,
                'longitude' => 36.2600000,
                'sequence' => 2,
                'estimated_arrival_time' => '07:25:00',
            ],
            [
                'route_id' => $route2->id,
                'stop_name' => 'البرامكة',
                'latitude' => 33.5097000,
                'longitude' => 36.2886000,
                'sequence' => 3,
                'estimated_arrival_time' => '07:40:00',
            ],
        ];

        foreach ($stops as $stop) {
            RouteStop::firstOrCreate(
                [
                    'route_id' => $stop['route_id'],
                    'sequence' => $stop['sequence'],
                ],
                [
                    'stop_name' => $stop['stop_name'],
                    'latitude' => $stop['latitude'],
                    'longitude' => $stop['longitude'],
                    'estimated_arrival_time' => $stop['estimated_arrival_time'],
                ]
            );
        }
    }
}

<?php


namespace Modules\Transport\database\seeders;

use App\Entities\Student;
use Illuminate\Database\Seeder;
use Modules\Transport\app\Entities\Route;
use Modules\Transport\app\Entities\RouteStop;
use Modules\Transport\app\Entities\Subscription;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::limit(2)->get();

        if ($students->count() < 2) {
            throw new \Exception(
                'At least 2 students are required before running SubscriptionSeeder.'
            );
        }

        $route1 = Route::where('name', 'الخط الأول - دمشق')->first();
        $route2 = Route::where('name', 'الخط الثاني - دمشق')->first();

        $stop1 = RouteStop::where('route_id', $route1->id)
            ->where('sequence', 1)
            ->first();

        $stop2 = RouteStop::where('route_id', $route2->id)
            ->where('sequence', 1)
            ->first();

        if (!$route1 || !$route2 || !$stop1 || !$stop2) {
            throw new \Exception(
                'Routes or Route Stops not found. Run previous seeders first.'
            );
        }

        Subscription::firstOrCreate(
            [
                'student_id' => $students[0]->id,
                'route_id' => $route1->id,
            ],
            [
                'route_stop_id' => $stop1->id,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonths(10)->toDateString(),
                'status' => 'active',
            ]
        );

        Subscription::firstOrCreate(
            [
                'student_id' => $students[1]->id,
                'route_id' => $route2->id,
            ],
            [
                'route_stop_id' => $stop2->id,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonths(10)->toDateString(),
                'status' => 'active',
            ]
        );
    }
}

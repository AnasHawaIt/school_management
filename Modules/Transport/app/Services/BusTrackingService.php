<?php

namespace Modules\Transport\app\Services;

use Exception;
use Modules\Transport\app\Entities\Bus;
use Modules\Transport\app\Entities\BusLocation;
use Modules\Transport\app\Entities\BusTrackingState;
use Modules\Transport\app\Entities\RouteStop;
use Modules\Transport\app\Events\BusEvents\BusStopStageChanged;


class BusTrackingService
{
    public function __construct(
        protected GeoapifyService $geoapifyService
    ) {
    }

    /**
     * Get current tracking status of the bus.
     */
    public function getCurrentStatus(Bus $bus): array
    {
        $location = $bus->locations()
            ->latest('recorded_at')
            ->first();

        if (!$location) {
            throw new Exception(
                'No location available for this bus.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Get assigned route
        |--------------------------------------------------------------------------
        */

        $route = $bus->routes()
            ->with([
                'stops' => function ($query) {
                    $query->orderBy('sequence');
                }
            ])
            ->first();

        if (!$route) {
            throw new Exception(
                'No route assigned to this bus.'
            );
        }

        $stops = $route->stops;

        if ($stops->isEmpty()) {
            throw new Exception(
                'No stops found for this route.'
            );
        }

        $nextStop = $this->findNextStop(
            $bus,
            $location,
            $stops
        );

        if (!$nextStop) {
            throw new Exception(
                'No next stop available.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Calculate route using Geoapify
        |--------------------------------------------------------------------------
        */

        $routeData = $this->geoapifyService->calculateRoute(
            (float) $location->latitude,
            (float) $location->longitude,
            (float) $nextStop->latitude,
            (float) $nextStop->longitude
        );

        /*
        |--------------------------------------------------------------------------
        | 6. Extract Geoapify data
        |--------------------------------------------------------------------------
        */

        $properties = $routeData['features'][0]['properties'] ?? [];

        $distance = isset($properties['distance'])
            ? (float) $properties['distance']
            : null;

        $duration = isset($properties['time'])
            ? (float) $properties['time']
            : null;

        /*
        |--------------------------------------------------------------------------
        | 7. Calculate ETA
        |--------------------------------------------------------------------------
        */

        $etaMinutes = $duration !== null
            ? (int) round($duration / 60)
            : null;

        $stopStatus = $this->getStopStatus($distance);

        $this->handleStopStage(
            $bus,
            $nextStop,
            $stopStatus,
            $distance,
            $etaMinutes ?? 0
        );

        return [

            'bus' => [
                'id' => $bus->id,
                'plate_number' => $bus->plate_number,
                'status' => $bus->status,
            ],

            'current_location' => [
                'latitude' => (float) $location->latitude,
                'longitude' => (float) $location->longitude,
                'speed' => $location->speed,
                'heading' => $location->heading,
                'accuracy' => $location->accuracy,
                'recorded_at' => $location->recorded_at,
            ],

            'route' => [
                'id' => $route->id,
                'name' => $route->name,
            ],

            'next_stop' => [
                'id' => $nextStop->id,
                'name' => $nextStop->stop_name,
                'latitude' => (float) $nextStop->latitude,
                'longitude' => (float) $nextStop->longitude,
                'sequence' => $nextStop->sequence,
                'status' => $stopStatus,
            ],

            'distance_meters' => $distance,

            'duration_seconds' => $duration,

            'eta_minutes' => $etaMinutes,
        ];
    }

    /**
     * Find the next stop.
     *
     * For now we select the closest stop that has not
     * already been passed based on route sequence.
     */

    protected function findNextStop(
        Bus $bus,
        BusLocation $location,
        $stops
    ): ?RouteStop {
        if ($stops->isEmpty()) {
            return null;
        }


        $lastState = $bus->trackingStates()
            ->latest('updated_at')
            ->first();


        $minSequence = $lastState?->routeStop?->sequence ?? 0;

        $candidateStops = $stops
            ->filter(function (RouteStop $stop) use ($minSequence) {
                return $stop->sequence > $minSequence;
            })
            ->values();


        if ($candidateStops->isEmpty()) {
            return $stops->last();
        }



        $nearest = null;
        $shortestDistance = PHP_FLOAT_MAX;

        foreach ($candidateStops as $stop) {
            $distance = $this->distance(
                (float) $location->latitude,
                (float) $location->longitude,
                (float) $stop->latitude,
                (float) $stop->longitude
            );

            if ($distance < $shortestDistance) {
                $shortestDistance = $distance;
                $nearest = $stop;
            }
        }

        return $nearest;
    }
    /**
     * Determine status of the next stop.
     */
    protected function getStopStatus(?float $distance): string
    {
        if ($distance === null) {
            return 'upcoming';
        }

        if ($distance <= 30) {
            return 'arrived';
        }

        if ($distance <= 100) {
            return 'arriving';
        }

        if ($distance <= 500) {
            return 'near';
        }

        if ($distance <= 1000) {
            return 'approaching';
        }

        return 'upcoming';
    }

    /**
     * Calculate distance between two coordinates
     * using Haversine formula.
     */

    protected function distance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {

        $earthRadius = 6371000;

        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);

        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);

        $a =
            sin($deltaLat / 2) ** 2 +
            cos($lat1) *
            cos($lat2) *
            sin($deltaLon / 2) ** 2;

        $c = 2 * atan2(
                sqrt($a),
                sqrt(1 - $a)
            );

        return $earthRadius * $c;
    }

    protected function handleStopStage(
        Bus $bus,
        RouteStop $stop,
        string $stage,
        ?float $distance,
        int $etaMinutes
    ): void {
        if ($distance === null) {
            return;
        }

        $trackingState = BusTrackingState::firstOrCreate(
            [
                'bus_id' => $bus->id,
                'route_stop_id' => $stop->id,
            ],
            [
                'stage' => 'upcoming',
            ]
        );

        $oldStage = $trackingState->stage;

        // لا يوجد تغيير
        if ($oldStage === $stage) {
            return;
        }

        $trackingState->update([
            'stage' => $stage,
            'last_notified_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Dispatch stage changed event
        |--------------------------------------------------------------------------
        */

        BusStopStageChanged::dispatch(
            $bus,
            $stop,
            $oldStage,
            $stage,
            $distance,
            $etaMinutes
        );
    }
}

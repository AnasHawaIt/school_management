<?php


namespace Modules\Transport\app\Services;

use Illuminate\Http\Request;
use Modules\Transport\app\Entities\Bus;
use Modules\Transport\app\Repositories\Interfaces\BusLocationRepositoryInterface;

class BusLocationService
{
    protected BusLocationRepositoryInterface $repo;

    public function __construct(
        BusLocationRepositoryInterface $repo
    )
    {
        $this->repo = $repo;
    }

    /**
     * Get all bus locations.
     */
    public function getAll(Request $request)
    {
        return $this->repo->getAll($request);
    }

    /**
     * Get a specific bus location.
     */
    public function find($id)
    {
        return $this->repo->find($id);
    }

    /**
     * Record a new GPS location for a bus.
     */
    public function store(Bus $bus, array $data)
    {
        // Make sure the bus is active.
        if ($bus->status !== 'active') {
            throw new \Exception(
                'Cannot record location for an inactive bus.'
            );
        }

        $location = $this->repo->create([
            'bus_id' => $bus->id,
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'speed' => $data['speed'] ?? null,
            'heading' => $data['heading'] ?? null,
            'accuracy' => $data['accuracy'] ?? null,
            'recorded_at' => $data['recorded_at'] ?? now(),
        ]);

        return $location->load('bus');
    }

    /**
     * Get the latest location of a bus.
     */
    public function getLatestByBus($busId)
    {
        return $this->repo->getLatestByBus($busId);
    }

    /**
     * Get the location history of a bus.
     */
    public function getByBus($busId, Request $request)
    {
        return $this->repo->getByBus(
            $busId,
            $request
        );
    }

    /**
     * Delete a location.
     */
    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}

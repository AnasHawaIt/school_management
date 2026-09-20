<?php

namespace Modules\Transport\app\Repositories\Eloquent;

use Illuminate\Http\Request;
use Modules\Transport\app\Entities\BusLocation;
use Modules\Transport\app\Repositories\Interfaces\BusLocationRepositoryInterface;

class BusLocationRepository implements BusLocationRepositoryInterface
{
    /**
     * Get all bus locations with pagination.
     */
    public function getAll(Request $request)
    {
        return BusLocation::with('bus')
            ->latest('recorded_at')
            ->paginate(
                $request->get('per_page', 10)
            );
    }

    /**
     * Create a new bus location.
     */
    public function create(array $data)
    {
        return BusLocation::create($data);
    }

    /**
     * Find a bus location by ID.
     */
    public function find($id)
    {
        return BusLocation::with('bus')
            ->findOrFail($id);
    }

    /**
     * Get locations for a specific bus.
     */
    public function getByBus($busId, Request $request)
    {
        return BusLocation::where('bus_id', $busId)
            ->latest('recorded_at')
            ->paginate(
                $request->get('per_page', 50)
            );
    }

    /**
     * Get the latest location of a bus.
     */
    public function getLatestByBus($busId)
    {
        return BusLocation::where('bus_id', $busId)
            ->latest('recorded_at')
            ->first();
    }

    /**
     * Update a bus location.
     */
    public function update($id, array $data)
    {
        $location = $this->find($id);

        $location->update($data);

        return $location->fresh('bus');
    }

    /**
     * Delete a bus location.
     */
    public function delete($id)
    {
        $location = $this->find($id);

        return $location->delete();
    }
}

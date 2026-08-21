<?php


namespace Modules\Transport\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Transport\Entities\Bus;
use Modules\Transport\Services\BusLocationService;

class BusLocationController extends Controller
{
    protected BusLocationService $service;

    public function __construct(
        BusLocationService $service
    )
    {
        $this->service = $service;
    }

    /**
     * Get all bus locations.
     */
    public function index(Request $request)
    {
        $locations = $this->service->getAll($request);

        return response()->json([
            'success' => true,
            'message' => 'Bus locations retrieved successfully.',
            'data' => $locations,
        ]);
    }

    /**
     * Store a new GPS location.
     */
    public function store(Request $request, Bus $bus)
    {
        $validated = $request->validate([
            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],

            'speed' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'heading' => [
                'nullable',
                'numeric',
                'between:0,360',
            ],

            'accuracy' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'recorded_at' => [
                'nullable',
                'date',
            ],
        ]);

        $location = $this->service->store(
            $bus,
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Bus location recorded successfully.',
            'data' => $location,
        ], 201);
    }

    /**
     * Get the latest location of a bus.
     */
    public function latest(Bus $bus)
    {
        $location = $this->service
            ->getLatestByBus($bus->id);

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'No location found for this bus.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Latest bus location retrieved successfully.',
            'data' => $location,
        ]);
    }

    /**
     * Get location history of a bus.
     */
    public function history(Request $request, Bus $bus)
    {
        $locations = $this->service->getByBus(
            $bus->id,
            $request
        );

        return response()->json([
            'success' => true,
            'message' => 'Bus location history retrieved successfully.',
            'data' => $locations,
        ]);
    }

    /**
     * Delete a bus location.
     */
    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Bus location deleted successfully.',
        ]);
    }
}

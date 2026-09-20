<?php

namespace Modules\Transport\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Transport\app\Http\Requests\StoreBusRequest;
use Modules\Transport\app\Http\Requests\UpdateBusRequest;
use Modules\Transport\app\Http\Resources\BusResource;
use Modules\Transport\app\Services\BusService;

class BusController extends Controller
{
    protected $service;

    public function __construct(BusService $service)
    {
        $this->service = $service;
    }

    public function restore($id)
    {
        return new BusResource($this->service->restore($id));
    }

    public function forceDelete($id)
    {
        return new BusResource($this->service->forceDelete($id));
    }

    public function AllOnlyTrashed()
    {
        return new BusResource($this->service->getBusesOnlyTrashed(  ));
    }


    public function index(Request $request)
    {
        return new BusResource($this->service->getAll( $request ));
    }

    public function store(StoreBusRequest $request)
    {
        return new BusResource(
            $this->service->create($request->validated())
        );
    }

    public function update(UpdateBusRequest $request, $id)
    {
        return new BusResource(
            $this->service->update($id, $request->validated())
        );
    }

    public function show($id)
    {
        return new BusResource($this->service->find($id));
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}

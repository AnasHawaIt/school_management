<?php

namespace Modules\Transport\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Library\Http\Requests\StoreBusRequest;
use Modules\Transport\Http\Requests\UpdateBusRequest;
use Modules\Transport\Http\Resources\BusResource;
use Modules\Transport\Services\BusService;

class BusController extends Controller
{
    protected $service;

    public function __construct(BusService $service)
    {
        $this->service = $service;
    }


    public function index(Request $request)
    {
        return BusResource::collection($this->service->getAll( $request ));
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

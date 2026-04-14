<?php

namespace Modules\Transport\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Transport\Http\Requests\StoreRouteStopRequest;
use Modules\Transport\Http\Requests\UpdateRouteStopRequest;
use Modules\Transport\Http\Resources\RouteStopResource;
use Modules\Transport\Repositories\Interfaces\RouteStopRepositoryInterface;
use Modules\Transport\Services\RouteStopService;

class RouteStopController extends Controller
{

    protected $service;

    public function __construct(RouteStopService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return RouteStopResource::collection($this->service->getAll( $request ));
    }

    public function store(StoreRouteStopRequest $request)
    {
        return new RouteStopResource($this->service->create($request->all()));
    }

    public function show($id)
    {
        return new RouteStopResource($this->service->find($id));
    }

    public function update(UpdateRouteStopRequest $request, $id)
    {
        return new RouteStopResource($this->service->update($id, $request->all()));
    }


    public function reorder($routeId)
    {
        $this->service->reorder($routeId);

        return response()->json([
            'message' => 'Stops reordered successfully'
        ]);
    }


    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}

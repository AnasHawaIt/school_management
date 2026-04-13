<?php

namespace Modules\Transport\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Transport\app\Http\Requests\StoreRouteStopRequest;
use Modules\Transport\app\Http\Requests\UpdateRouteStopRequest;
use Modules\Transport\app\Http\Resources\RouteStopResource;
use Modules\Transport\Events\RouteStopEvents\MemberDeleted;
use Modules\Transport\Events\RouteStopEvents\MemberRestored;
use Modules\Transport\Services\MemberService;

class RouteStopController extends Controller
{

    protected $service;

    public function __construct(MemberService $service)
    {
        $this->service = $service;
    }

    public function AllOnlyTrashed()
    {
        return new RouteStopResource($this->service->getRouteStopsOnlyTrashed());
    }

    public function restore($id)
    {
        return new RouteStopResource($this->service->restore($id));
    }

    public function forceDelete($id)
    {
        return new RouteStopResource($this->service->forceDelete($id));
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

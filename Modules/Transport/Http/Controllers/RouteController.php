<?php

namespace Modules\Transport\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Transport\Events\BusEvents\BusDeleted;
use Modules\Transport\Events\BusEvents\BusRestored;
use Modules\Transport\Events\RouteEvents\RouteDeleted;
use Modules\Transport\Events\RouteEvents\RouteRestored;
use Modules\Transport\Http\Requests\StoreRouteRequest;
use Modules\Transport\Http\Requests\UpdateRouteRequest;
use Modules\Transport\Http\Requests\UpdateRouteStopRequest;
use Modules\Transport\Http\Resources\RouteResource;
use Modules\Transport\Repositories\Interfaces\RouteRepositoryInterface;
use Modules\Transport\Services\RouteService;

class RouteController extends Controller
{
    protected $service;

    public function __construct(RouteService $service)
    {
        $this->service = $service;
    }

    public function AllOnlyTrashed()
    {
        return RouteResource::collection($this->service->getRoutesOnlyTrashed(  ));
    }

    public function restore($id)
    {
        $bus = $this->service->restore($id);

        event(new RouteRestored($bus));

        return response()->json($bus);
    }

    public function forceDelete($id)
    {
        $bus = $this->service->forceDelete($id);

        event(new RouteDeleted($bus));

        return response()->json($bus);
    }

    public function index(Request $request)
    {
        return RouteResource::collection($this->service->getAll( $request ));
    }

    public function store(StoreRouteRequest $request)
    {
        return new RouteResource(
            $this->service->create($request->validated())
        );
    }

    public function update(UpdateRouteRequest $request, $id)
    {
        return new RouteResource(
            $this->service->update($id, $request->validated())
        );
    }
    public function show($id)
    {
        return new RouteResource($this->service->find($id));
    }


    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}

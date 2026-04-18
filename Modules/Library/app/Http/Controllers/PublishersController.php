<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Library\app\Http\Requests\StorePuiblshersRequest;
use Modules\Library\app\Http\Requests\UpdatePublishersRequest;
use Modules\Library\app\Http\Resources\PublishersResource;
use Modules\Library\Services\PublishersService;

class PublishersController extends Controller
{
    protected $service;

    public function __construct(PublishersService $service)
    {
        $this->service = $service;
    }

    public function restore($id)
    {
        return new PublishersResource( $this->service->restore($id));
    }

    public function forceDelete($id)
    {
        $this->service->forceDelete($id);

        return response()->json([
            'message' => 'Force deleted successfully'
        ]);
    }

    public function AllOnlyTrashed()
    {
        return PublishersResource::collection(
            $this->service->getPublishersOnlyTrashed()
        );
    }

    public function index(Request $request)
    {
        return  PublishersResource::collection($this->service->getAll($request));
    }

    public function store(StorePuiblshersRequest $request)
    {
        return new PublishersResource($this->service->create($request->all()));
    }

    public function show($id)
    {
        return new PublishersResource($this->service->findById($id));
    }

    public function update(UpdatePublishersRequest $request, $id )
    {
        return new PublishersResource($this->service->update($id, $request->all()));
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}

<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Library\app\Http\Requests\StoreAuthorRequest;
use Modules\Library\app\Http\Requests\UpdateAuthorRequest;
use Modules\Library\app\Http\Resources\AuthorResource;
use Modules\Library\Services\AuthorService;

class AuthorController extends Controller
{
    protected $service;

    public function __construct(AuthorService $service)
    {
        $this->service = $service;
    }

    public function restore($id)
    {
        return new AuthorResource( $this->service->restore($id));
    }

    public function forceDelete($id)
    {
        return new AuthorResource($this->service->forceDelete($id));
    }

    public function AllOnlyTrashed()
    {
        return new AuthorResource($this->service->getAuthorOnlyTrashed());
    }

    public function index( $request)
    {
        return AuthorResource::collection($this->service->getAll( $request ));
    }

    public function store(StoreAuthorRequest $request)
    {
        return new AuthorResource($this->service->create($request->all()));
    }

    public function show($id)
    {
        return new AuthorResource($this->service->find($id));
    }

    public function update(UpdateAuthorRequest $request, $id)
    {
        return new AuthorResource($this->service->update($id, $request->all()));
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}

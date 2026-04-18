<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $this->service->forceDelete($id);

        return response()->json([
            'message' => 'Force deleted successfully'
        ]);
    }

    public function AllOnlyTrashed()
    {

        return AuthorResource::collection(
            $this->service->getAuthorOnlyTrashed()
        );

    }

    public function index(Request $request)
    {
        return AuthorResource::collection($this->service->getAll( $request ));
    }

    public function store(StoreAuthorRequest $request)
    {
        $data =[
            'name' => $request->name,
            'birth_date' => $request->birth_date,
            'death_date' => $request->death_date,
            ];

        $images = $request->file('images');


        return new AuthorResource($this->service->create($data,$images ));
    }

    public function show($id)
    {
        return new AuthorResource($this->service->find($id));
    }

    public function update(UpdateAuthorRequest $request, $id)
    {
        $data =$request->only([
            'name',
            'birth_date',
            'death_date'
        ]);

        $images = $request->file('images');

         return new AuthorResource($this->service->update($id, $data ,$images ));
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}

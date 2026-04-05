<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Library\Http\Requests\StoreAuthorRequest;
use Modules\Library\Http\Requests\UpdateAuthorRequest;
use Modules\Library\Http\Resources\AuthorResource;
use Modules\Library\Repositories\Interfaces\AuthorRepositoryInterface;

class AuthorController extends Controller
{
    protected $authorRepo;

    public function __construct(AuthorRepositoryInterface $authorRepo)
    {
        $this->authorRepo = $authorRepo;
    }

    public function index(Request $request)
    {
        return AuthorResource::collection($this->authorRepo->getAll( $request ));
    }

    public function store(StoreAuthorRequest $request)
    {
        return new AuthorResource($this->authorRepo->create($request->all()));
    }

    public function show($id)
    {
        return new AuthorResource($this->authorRepo->findById($id));
    }

    public function update(UpdateAuthorRequest $request, $id)
    {
        return new AuthorResource($this->authorRepo->update($id, $request->all()));
    }

    public function destroy($id)
    {
        $this->authorRepo->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}

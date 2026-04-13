<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Library\app\Http\Requests\StoreCategoryRequest;
use Modules\Library\app\Http\Requests\UpdateCategoryRequest;
use Modules\Library\app\Http\Resources\CategoryResource;
use Modules\Library\Services\CategoryService;

class CategoryController extends Controller
{
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function restore($id)
    {
        return new CategoryResource( $this->service->restore($id));
    }

    public function forceDelete($id)
    {
        return new CategoryResource($this->service->forceDelete($id));
    }

    public function AllOnlyTrashed()
    {
        return new CategoryResource($this->service->getCategoryOnlyTrashed());
    }

    public function index(Request $request)
    {
        return new CategoryResource($this->service->getAll($request));
    }

    public function store(StoreCategoryRequest $request)
    {
        return new CategoryResource($this->service->create($request->all()));
    }

    public function show($id)
    {
        return new CategoryResource($this->service->findById($id));
    }

    public function update(UpdateCategoryRequest $request, $id )
    {
        return new CategoryResource($this->service->update($id, $request->all()));
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}

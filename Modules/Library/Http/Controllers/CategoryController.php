<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Library\Entities\Category;
use Modules\Library\Http\Requests\StoreCategoryRequest;
use Modules\Library\Http\Requests\UpdateCategoryRequest;
use Modules\Library\Http\Resources\CategoryResource;
use Modules\Library\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    protected $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function index(Request $request)
    {
        return CategoryResource::collection($this->categoryRepo->getAll($request));
    }

    public function store(StoreCategoryRequest $request)
    {
        return new CategoryResource($this->categoryRepo->create($request->all()));
    }

    public function show($id)
    {
        return new CategoryResource($this->categoryRepo->findById($id));
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        return new CategoryResource($this->categoryRepo->update($id, $request->all()));
    }

    public function destroy($id)
    {
        $this->categoryRepo->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}

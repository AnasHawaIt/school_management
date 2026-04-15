<?php


namespace Modules\Library\Repositories\Eloquent;

use Modules\Library\Entities\Category;
use Modules\Library\Filters\CategoryFilter;
use Modules\Library\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getCategoryOnlyTrashed()
    {
        return Category::onlyTrashed()->paginate(10);
    }

    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);

        $category->restore();

        return $category;
    }

    public function forceDelete($id)
    {
        $category = Category::withTrashed()->findOrFail($id);

        $category->forceDelete();

        return $category;
    }

    public function getAll($request)
    {
        $query = Category::query();

        $query = (new CategoryFilter($request))->apply($query);

        return $query->paginate($request->get('per_page', 10));
    }

    public function findById($id)
    {
        return Category::findOrFail($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update($id, array $data)
    {
        $category = $this->findById($id);
        $category->update($data);
        return $category;
    }

    public function delete($id)
    {
        $category = $this->findById($id);
        return $category->delete();
    }
}

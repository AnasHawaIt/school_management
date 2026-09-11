<?php

namespace Modules\Library\Services;

use Modules\library\Events\CategoryEvents\CategoryCreated;
use Modules\Library\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryService
{
    protected $repo;

    public function __construct(CategoryRepositoryInterface $repo,
    ) {
        $this->repo = $repo;
    }

    public function getCategoryOnlyTrashed()
    {
        return $this->repo->getCategoryOnlyTrashed();
    }

    public function restore($id)
    {
        $category= $this->repo->restore($id);

        event(new CategoryCreated($category));


        return $category;
    }

    public function forceDelete($id)
    {

        $category= $this->repo->findById($id);

        $category->forceDelete();

        event(new CategoryCreated($category));


        return true;
    }

    public function getAll($request)
    {
        return $this->repo->getAll($request);
    }


    public function create(array $data)
    {
        $category= $this->repo->create($data);

        event(new CategoryCreated($category));


        return $category;
    }

    public function findById($id)
    {
        return $this->repo->findById($id);
    }

    public function update($id, array $data)
    {
        $category= $this->repo->update($id, $data);

        event(new CategoryCreated($category));


        return $category;
    }

    public function delete($id)
    {
        $category = $this->repo->findById($id);

        if (!$category) {
            throw new \Exception('Category not found');
        }

        $this->repo->delete($id);

        event(new CategoryCreated($category));


        return true;
    }
}

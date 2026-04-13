<?php

namespace Modules\Library\Services;

use Modules\library\Events\CategoryEvents\CategoryCreated;
use Modules\library\Events\CategoryEvents\CategoryDeleted;
use Modules\library\Events\CategoryEvents\CategoryRestored;
use Modules\library\Events\CategoryEvents\CategoryUpdated;
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
        $bus= $this->repo->restore($id);

        event(new CategoryRestored($bus));

        return $bus;
    }

    public function forceDelete($id)
    {
        $bus= $this->repo->forceDelete($id);

        event(new CategoryDeleted($bus));

        return true;
    }

    public function getAll($request)
    {
        return $this->repo->getAll($request);
    }

    public function create(array $data)
    {
        $category= $this->repo->create($data);

        event(new CategoryCreated($category),auth()->id());

        return $category;
    }

    public function findById($id)
    {
        return $this->repo->findById($id);
    }

    public function update($id, array $data)
    {
        $category= $this->repo->update($id, $data);

        event(new CategoryUpdated($category),auth()->id());

        return $category;
    }

    public function delete($id)
    {
        $category = $this->repo->findById($id);

        if (!$category) {
            throw new \Exception('Bus not found');
        }

        $this->repo->delete($id);

        event(new CategoryDeleted($category));

        return true;
    }
}

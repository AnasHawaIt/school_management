<?php

namespace Modules\Library\Services;

use Modules\Library\app\Http\Resources\PublishersResource;
use Modules\Library\Events\PublishersEvents\PublishersCreated;
use Modules\Library\Events\PublishersEvents\PublishersDeleted;
use Modules\Library\Events\PublishersEvents\PublishersRestored;
use Modules\Library\Events\PublishersEvents\PublishersUpdated;
use Modules\Library\Repositories\Eloquent\PublishersRepository;

class PublishersService
{
    protected $repo;

    public function __construct(PublishersRepository $repo,
    ) {
        $this->repo = $repo;
    }

    public function getPublishersOnlyTrashed()
    {
        return $this->repo->getPublishersOnlyTrashed();
    }

    public function restore($id)
    {
        $publishers= $this->repo->restore($id);

        event(new PublishersRestored($publishers));

        return $publishers;
    }

    public function forceDelete($id)
    {
        $publishers= $this->repo->forceDelete($id);

        event(new PublishersDeleted($publishers));

        return true;
    }

    public function getAll($request)
    {
        return new PublishersResource($this->repo->getAll($request));
    }

    public function create(array $data)
    {
        $category= $this->repo->create($data);

        event(new PublishersCreated($category),auth()->id());

        return $category;
    }

    public function findById($id)
    {
        return $this->repo->findById($id);
    }

    public function update($id, array $data)
    {
        $category= $this->repo->update($id, $data);

        event(new PublishersUpdated($category),auth()->id());

        return $category;
    }

    public function delete($id)
    {
        $category = $this->repo->findById($id);

        if (!$category) {
            throw new \Exception('Publisher not found');
        }

        $this->repo->delete($id);

        event(new PublishersDeleted($category));

        return true;
    }
}

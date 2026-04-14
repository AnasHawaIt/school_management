<?php

namespace Modules\Library\Services;

use Modules\library\Events\AuthorEvents\AuthorCreated;
use Modules\library\Events\AuthorEvents\AuthorDeleted;
use Modules\library\Events\AuthorEvents\AuthorRestored;
use Modules\library\Events\AuthorEvents\AuthorUpdated;
use Modules\Library\Repositories\Interfaces\AuthorRepositoryInterface;

class AuthorService
{
    protected $repo;

    public function __construct(AuthorRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAuthorOnlyTrashed()
    {
        return $this->repo->getAuthorOnlyTrashed();
    }

    public function restore($id)
    {
        $bus= $this->repo->restore($id);

        event(new AuthorRestored($bus));

        return $bus;
    }

    public function forceDelete($id)
    {
        $bus= $this->repo->forceDelete($id);

        event(new AuthorDeleted($bus));

        return true;
    }

    public function getAll($request)
    {
        return $this->repo->getAll($request);
    }

    public function create(array $data)
    {
        $bus = $this->repo->create($data);

        event(new AuthorCreated($bus, auth()->id()));

        return $bus;
    }

    public function update($id, array $data)
    {
        $bus= $this->repo->update($id, $data);

        event(new AuthorUpdated($bus, auth()->id()));

        return $bus;
    }

    public function delete($id)
    {
        $bus = $this->repo->find($id);

        if (!$bus) {
            throw new \Exception('Bus not found');
        }

        $this->repo->delete($id);

        event(new AuthorDeleted($bus));

        return true;
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

}

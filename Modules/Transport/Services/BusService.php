<?php

namespace Modules\Transport\Services;

use Modules\Transport\Events\BusEvents\BusCreated;
use Modules\Transport\Events\BusEvents\BusDeleted;
use Modules\Transport\Events\BusEvents\BusRestored;
use Modules\Transport\Events\BusEvents\BusUpdated;
use Modules\Transport\Repositories\Interfaces\BusRepositoryInterface;

class BusService
{
    protected $repo;

    public function __construct(BusRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getBusesOnlyTrashed()
    {
        return $this->repo->getBusesOnlyTrashed();
    }


    public function restore($id)
    {
        $bus= $this->repo->restore($id);

        event(new BusRestored($bus));

        return $bus;
    }

    public function forceDelete($id)
    {
        $bus= $this->repo->forceDelete($id);

        event(new BusDeleted($bus));

        return true;
    }

    public function getAll($request)
    {
        return $this->repo->getAll($request);
    }

    public function create(array $data)
    {
        $bus = $this->repo->create($data);

        event(new BusCreated($bus, auth()->id()));

        return $bus;
    }

    public function update($id, array $data)
    {
        $bus= $this->repo->update($id, $data);

        event(new BusUpdated($bus, auth()->id()));

        return $bus;
    }

        public function delete($id)
    {
        $bus = $this->repo->find($id);

        if (!$bus) {
            throw new \Exception('Bus not found');
        }

        $this->repo->delete($id);

        event(new BusDeleted($bus));

        return true;
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }
}

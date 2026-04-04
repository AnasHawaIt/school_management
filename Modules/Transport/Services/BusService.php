<?php

namespace Modules\Transport\Services;

use Modules\Transport\Repositories\Interfaces\BusRepositoryInterface;

class BusService
{
    protected $repo;

    public function __construct(BusRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAll($request)
    {
        return $this->repo->getAll($request);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }
}

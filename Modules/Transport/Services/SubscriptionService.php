<?php

namespace Modules\Transport\Services;

use Modules\Transport\Repositories\Interfaces\SubscriptionRepositoryInterface;

class SubscriptionService
{
    protected $repo;

    public function __construct(SubscriptionRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function getStudentSubscriptions($studentId)
    {
        return $this->repo->getByStudent($studentId);
    }
}

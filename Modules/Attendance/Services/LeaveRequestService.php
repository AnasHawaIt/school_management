<?php

namespace Modules\Attendance\Services;

use Modules\Attendance\Contracts\Services\LeaveRequestServiceInterface;
use Modules\Attendance\Contracts\Repositories\LeaveRequestRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class LeaveRequestService implements LeaveRequestServiceInterface
{
    public function __construct(
        protected LeaveRequestRepositoryInterface $repository,
    ) {}

    public function getAll(array $filters = [])
    {
        return $this->repository->getAll($filters);
    }

    public function findById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function createRequest(array $data): object
    {
        $data['created_by'] = Auth::id();
        return $this->repository->create($data);
    }

    public function updateRequest(int $id, array $data): object
    {
        $request = $this->repository->findById($id);

        // لا يمكن تعديل طلب تمت مراجعته
        if ($request->status !== 'pending') {
            throw new \Exception('Cannot update a request that has already been reviewed.');
        }

        return $this->repository->update($id, $data);
    }

    public function deleteRequest(int $id): bool
    {
        $request = $this->repository->findById($id);

        if ($request->status !== 'pending') {
            throw new \Exception('Cannot delete a request that has already been reviewed.');
        }

        return $this->repository->delete($id);
    }

    public function approve(int $id, int $reviewerId, ?string $notes = null): object
    {
        $request = $this->repository->findById($id);

        if ($request->status !== 'pending') {
            throw new \Exception('Request has already been reviewed.');
        }

        return $this->repository->approve($id, $reviewerId, $notes);
    }

    public function reject(int $id, int $reviewerId, ?string $notes = null): object
    {
        $request = $this->repository->findById($id);

        if ($request->status !== 'pending') {
            throw new \Exception('Request has already been reviewed.');
        }

        return $this->repository->reject($id, $reviewerId, $notes);
    }

    public function getPending()
    {
        return $this->repository->getPending();
    }

    public function getByRequestable(string $type, int $id)
    {
        // type: 'student' أو 'teacher'
        $morphMap = [
            'student' => \Modules\Academic\Entities\Student::class,
            'teacher' => \Modules\Academic\Entities\Teacher::class,
        ];

        if (!isset($morphMap[$type])) {
            throw new \Exception("Invalid requestable type: {$type}. Use 'student' or 'teacher'.");
        }

        return $this->repository->getByRequestable($morphMap[$type], $id);
    }
}

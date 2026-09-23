<?php

namespace Modules\Attendance\app\Services;

use Illuminate\Support\Facades\Auth;
use Modules\Academic\app\Entities\Student;
use Modules\Academic\app\Entities\Teacher;
use Modules\Academic\app\Events\LeaveRequests\LeaveRequestApproved;
use Modules\Academic\app\Events\LeaveRequests\LeaveRequestCreated;
use Modules\Academic\app\Events\LeaveRequests\LeaveRequestDeleted;
use Modules\Academic\app\Events\LeaveRequests\LeaveRequestRejected;
use Modules\Academic\app\Events\LeaveRequests\LeaveRequestUpdated;
use Modules\Attendance\app\Contracts\Repositories\LeaveRequestRepositoryInterface;
use Modules\Attendance\app\Contracts\Services\LeaveRequestServiceInterface;
use Modules\Attendance\Entities\LeaveRequest;

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

    public function createRequest(array $data): LeaveRequest
    {
        $userId = Auth::id();

        $data['created_by'] = $userId;

        $request = $this->repository->create($data);

        event(new LeaveRequestCreated(
            request: $request,
            userId: $userId,
        ));

        return $request;
    }

    public function updateRequest(int $id, array $data): LeaveRequest
    {
        $request = $this->repository->findById($id);

        // لا يمكن تعديل طلب تمت مراجعته
        if ($request->status !== 'pending') {
            throw new \Exception(
                'Cannot update a request that has already been reviewed.'
            );
        }

        $updated = $this->repository->update($id, $data);

        event(new LeaveRequestUpdated(
            request: $updated,
            changes: $updated->getChanges(),
            userId: Auth::id(),
        ));

        return $updated;
    }

    public function deleteRequest(int $id): bool
    {
        $request = $this->repository->findById($id);

        if ($request->status !== 'pending') {
            throw new \Exception(
                'Cannot delete a request that has already been reviewed.'
            );
        }

        $result = $this->repository->delete($id);

        if ($result) {
            event(new LeaveRequestDeleted(
                request: $request,
                userId: Auth::id(),
            ));
        }

        return $result;

    }

    public function approve(int $id, int $reviewerId, ?string $notes = null): LeaveRequest
    {
        $request = $this->repository->findById($id);

        if ($request->status !== 'pending') {
            throw new \Exception(
                'Request has already been reviewed.'
            );
        }

        $request = $this->repository->approve(
            $id,
            $reviewerId,
            $notes
        );

        event(new LeaveRequestApproved(
            request: $request,
            reviewerId: $reviewerId,
            notes: $notes,
        ));

        return $request;
    }

    public function reject(int $id, int $reviewerId, ?string $notes = null): LeaveRequest
    {
        $request = $this->repository->findById($id);

        if ($request->status !== 'pending') {
            throw new \Exception(
                'Request has already been reviewed.'
            );
        }

        $request = $this->repository->reject(
            $id,
            $reviewerId,
            $notes
        );

        event(new LeaveRequestRejected(
            request: $request,
            reviewerId: $reviewerId,
            notes: $notes,
        ));

        return $request;
    }

    public function getPending()
    {
        return $this->repository->getPending();
    }

    public function getByRequestable(string $type, int $id)
    {
        $morphMap = [
            'student' =>Student::class,
            'teacher' =>Teacher::class,
        ];

        if (!isset($morphMap[$type])) {
            throw new \Exception(
                "Invalid requestable type: {$type}.
                 Use 'student' or 'teacher'."
            );
        }

        return $this->repository->getByRequestable(
            $morphMap[$type],
            $id
        );
    }
}

<?php

namespace Modules\Attendance\app\Repositories;

use Modules\Attendance\app\Contracts\Repositories\LeaveRequestRepositoryInterface;
use Modules\Attendance\Entities\LeaveRequest;

class LeaveRequestRepository implements LeaveRequestRepositoryInterface
{
    public function __construct(protected LeaveRequest $model) {}

    public function getAll(array $filters = [])
    {
        $query = $this->model->with(['requestable.user', 'reviewer', 'creator']);

        if (!empty($filters['status']))           $query->where('status', $filters['status']);
        if (!empty($filters['type']))             $query->where('type', $filters['type']);
        if (!empty($filters['requestable_type'])) $query->where('requestable_type', $filters['requestable_type']);
        if (!empty($filters['from_date']))        $query->where('from_date', '>=', $filters['from_date']);
        if (!empty($filters['to_date']))          $query->where('to_date', '<=', $filters['to_date']);

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    public function findById(int $id)
    {
        return $this->model->with(['requestable.user', 'reviewer', 'creator'])->findOrFail($id);
    }

    public function create(array $data): LeaveRequest
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): LeaveRequest
    {
        $request = $this->model->findOrFail($id);
        $request->update($data);
        return $request->fresh(['requestable.user', 'reviewer']);
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function approve(int $id, int $reviewerId, ?string $notes = null): LeaveRequest
    {
        $request = $this->model->findOrFail($id);
        $request->update([
            'status'      => 'approved',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'admin_notes' => $notes,
        ]);
        return $request->fresh('requestable.user');
    }

    public function reject(int $id, int $reviewerId, ?string $notes = null): LeaveRequest
    {
        $request = $this->model->findOrFail($id);
        $request->update([
            'status'      => 'rejected',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'admin_notes' => $notes,
        ]);
        return $request->fresh('requestable.user');
    }

    public function getPending()
    {
        return $this->model->with(['requestable.user', 'creator'])
            ->where('status', 'pending')
            ->latest()
            ->get();
    }

    public function getByRequestable(string $type, int $id)
    {
        return $this->model->with('reviewer')
            ->where('requestable_type', $type)
            ->where('requestable_id', $id)
            ->latest()
            ->get();
    }
}

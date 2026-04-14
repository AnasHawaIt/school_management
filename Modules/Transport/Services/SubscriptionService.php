<?php

namespace Modules\Transport\Services;

use Illuminate\Support\Facades\DB;
use Modules\Transport\Entities\Route;
use Modules\Transport\Repositories\Interfaces\SubscriptionRepositoryInterface;

class SubscriptionService
{
    protected $repo;

    public function __construct(SubscriptionRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAll($request)
    {
        return $this->repo->getAll($request);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function subscribe(array $data)
    {
        DB::beginTransaction();

        try {
            $route = Route::with('bus')->findOrFail($data['route_id']);

            $count = $this->repo->countActiveByRoute($data['route_id']);

            if ($count >= $route->bus->capacity) {
                throw new \Exception('Bus is full');
            }

            $subscription = $this->repo->create([
                'student_id' => $data['student_id'],
                'route_id'   => $data['route_id'],
                'start_date' => $data['start_date'],
                'end_date'   => $data['end_date'],
                'status'     => 'active',
            ]);

            DB::commit();

            return $subscription;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        return $this->repo->update($id, $data);
    }

    public function cancel($id)
    {
        return $this->repo->update($id, [
            'status' => 'expired'
        ]);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    public function getStudentSubscriptions($studentId)
    {
        return $this->repo->getByStudent($studentId);
    }

    public function getActiveByRoute($routeId)
    {
        return $this->repo->getActiveByRoute($routeId);
    }
}

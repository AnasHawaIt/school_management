<?php

namespace Modules\Transport\Services;

use Illuminate\Support\Facades\DB;
use Modules\Transport\Entities\Route;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionCreated;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionDeleted;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionUpdated;
use Modules\Transport\Repositories\Interfaces\SubscriptionRepositoryInterface;

class SubscriptionService
{
    protected $repo;

    public function __construct(SubscriptionRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getSubscriptionOnlyTrashed()
    {
        return $this->repo->getSubscriptionOnlyTrashed();
    }

    public function restore($id)
    {
        return $this->repo->restore($id);
    }

    public function forceDelete($id)
    {
        return $this->repo->forceDelete($id);
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
        $subscription = $this->repo->create($data);

        event(new SubscriptionCreated($subscription),auth()->id());

        return $subscription;
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
        $subscription= $this->repo->update($id, $data);

        event(new SubscriptionUpdated($subscription),auth()->id());

        return $subscription;
    }

    public function cancel($id)
    {
        return $this->repo->update($id, [
            'status' => 'expired'
        ]);
    }

    public function delete($id)
    {
        $subscription = $this->repo->find($id);

        if (!$subscription) {
            throw new \Exception('Route not found');
        }

        $this->repo->delete($id);

        event(new SubscriptionDeleted($subscription),auth()->id());

        return true;
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

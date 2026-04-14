<?php
namespace Modules\Transport\Repositories\Eloquent;

use Modules\Transport\Entities\Subscription;
use Modules\Transport\Filters\SubscriptionFilter;
use Modules\Transport\Repositories\Interfaces\SubscriptionRepositoryInterface;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function getAll($request)
    {
        $query = Subscription::query();

        $query = (new SubscriptionFilter($request))->apply($query);

        return $query->paginate($request->get('per_page', 10));

    }

    public function create(array $data)
    {
        return Subscription::create($data);
    }

    public function find($id)
    {
        return Subscription::findOrFail($id);
    }

    public function getByStudent($studentId)
    {
        return Subscription::where('student_id', $studentId)->get();
    }

    public function update($id, array $data)
    {
        $subscription = $this->find($id);
        $subscription->update($data);

        return $subscription;
    }

    public function delete($id)
    {
        $subscription = $this->find($id);
        return $subscription->delete();
    }

    public function countActiveByRoute($routeId)
    {
        return Subscription::where('route_id', $routeId)
            ->where('status', 'active')
            ->count();
    }

    public function getActiveByRoute($routeId)
    {
        return Subscription::where('route_id', $routeId)
            ->where('status', 'active')
            ->get();
    }
}

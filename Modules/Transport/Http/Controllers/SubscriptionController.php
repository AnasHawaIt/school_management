<?php

namespace Modules\Transport\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionDeleted;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionRestored;
use Modules\Transport\Http\Requests\StoreSubscriptionRequest;
use Modules\Transport\Http\Requests\UpdateSubscriptionRequest;
use Modules\Transport\Http\Resources\SubscriptionResource;
use Modules\Transport\Services\SubscriptionService;


class SubscriptionController extends Controller
{

    protected $service;

    public function __construct(SubscriptionService $service)
    {
        $this->service = $service;
    }

    public function AllOnlyTrashed()
    {
        return SubscriptionResource::collection($this->service->getSubscriptionOnlyTrashed());
    }

    public function restore($id)
    {
        $bus = $this->service->restore($id);

        event(new SubscriptionRestored($bus));

        return response()->json($bus);
    }

    public function forceDelete($id)
    {
        $bus = $this->service->forceDelete($id);

        event(new SubscriptionDeleted($bus));

        return response()->json($bus);
    }

    public function index(Request $request)
    {
        return SubscriptionResource::collection(
            $this->service->getAll($request)
        );
    }

    public function show($id)
    {
        return new SubscriptionResource(
            $this->service->find($id)
        );
    }

    public function store(StoreSubscriptionRequest $request)
    {
        $subscription = $this->service->subscribe(
            $request->validated()
        );

        return new SubscriptionResource($subscription);
    }

    public function update(UpdateSubscriptionRequest $request, $id)
    {
        return new SubscriptionResource(
            $this->service->update($id, $request->validated())
        );
    }

    public function expire($id)
    {
        return new SubscriptionResource(
            $this->service->cancel($id)
        );
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }

}

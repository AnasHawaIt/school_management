<?php

namespace Modules\Transport\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Transport\app\Http\Requests\StoreSubscriptionRequest;
use Modules\Transport\app\Http\Requests\UpdateSubscriptionRequest;
use Modules\Transport\app\Http\Resources\SubscriptionResource;
use Modules\Transport\Events\SubscriptionEvents\TransactionDeleted;
use Modules\Transport\Events\SubscriptionEvents\TransactionRestored;
use Modules\Transport\Services\TransactionService;


class SubscriptionController extends Controller
{

    protected $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function AllOnlyTrashed()
    {
        return new SubscriptionResource($this->service->getSubscriptionOnlyTrashed());
    }

    public function restore($id)
    {
        return new SubscriptionResource($this->service->restore($id));
    }

    public function forceDelete($id)
    {
        return new SubscriptionResource($this->service->forceDelete($id));
    }

    public function index(Request $request)
    {
        return new SubscriptionResource(
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

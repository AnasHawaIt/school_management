<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Library\app\Http\Requests\StoreTransactionRequest;
use Modules\Library\app\Http\Requests\UpdateTransactionRequest;
use Modules\Library\app\Http\Resources\TransactionResource;
use Modules\Library\Services\TransactionService;

class TransactionController extends Controller
{
    protected $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function restore($id)
    {
        return new TransactionResource( $this->service->restore($id));
    }

    public function forceDelete($id)
    {
        $this->service->forceDelete($id);

        return response()->json([
            'message' => 'Force deleted successfully'
        ]);
    }

    public function AllOnlyTrashed()
    {
        return TransactionResource::collection($this->service->getTransactionOnlyTrashed());
    }

    public function index(Request $request)
    {
        $transactions = $this->service->getAll($request);

        return  TransactionResource::collection($transactions);
    }

    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();
        $data['status'] = 'borrowed';

        $transaction = $this->service->create($data);

        return new TransactionResource($transaction);
    }

    public function show($id)
    {
        $transaction = $this->service->findById($id);

        return new TransactionResource($transaction);
    }

    public function update(UpdateTransactionRequest $request, $id)
    {
        $transaction = $this->service->update($id, $request->validated());

        return new TransactionResource($transaction);
    }

    public function renew($id)
    {
        return new TransactionResource($this->service->renew($id));
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json(['message' => 'Deleted']);
    }

}

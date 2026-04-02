<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Library\Entities\Transaction;
use Modules\Library\Http\Requests\StoreTransactionRequest;
use Modules\Library\Http\Requests\UpdateTransactionRequest;
use Modules\Library\Http\Resources\TransactionResource;
use Modules\Library\Repositories\Interfaces\TransactionRepositoryInterface;

class TransactionController extends Controller
{
    protected $transactionRepo;

    public function __construct(TransactionRepositoryInterface $transactionRepo)
    {
        $this->transactionRepo = $transactionRepo;
    }

    public function index(Request $request)
    {

        $transactions = Transaction::with(['book', 'member'])->get($request);

        return TransactionResource::collection($transactions);
    }

    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();
        $data['status'] = 'borrowed';

        $transaction = $this->transactionRepo->create($data);

        return new TransactionResource($transaction);
    }

    public function show($id)
    {
        $transaction = $this->transactionRepo->findById($id);

        return new TransactionResource($transaction);
    }

    public function update(UpdateTransactionRequest $request, $id)
    {
        $transaction = $this->transactionRepo->update($id, $request->all());
        return new TransactionResource($transaction);
    }

    public function destroy($id)
    {
        $this->transactionRepo->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}

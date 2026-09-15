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
    protected TransactionService $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function statusDashboard()
    {
        return response()->json(
            $this->service->getStatusDashboard()
        );
    }

    /**
     * Get all transactions.
     */
    public function index(Request $request)
    {
        $transactions = $this->service->getAll($request);

        return TransactionResource::collection($transactions);
    }

    /**
     * Create a new borrowing.
     */
    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();

        $transaction = $this->service->create($data);

        return new TransactionResource($transaction);
    }

    /**
     * Show one transaction.
     */
    public function show(int $id)
    {
        $transaction = $this->service->findById($id);

        return new TransactionResource($transaction);
    }

    /**
     * Update transaction.
     *
     * Use this for normal editable fields.
     * Status transitions should use dedicated methods:
     * approve, reject, pickup, cancel, return, renew, etc.
     */
    public function update(UpdateTransactionRequest $request, int $id)
    {
        $transaction = $this->service->update(
            $id,
            $request->validated()
        );

        return new TransactionResource($transaction);
    }

    /**
     * Approve pending borrowing.
     */
    public function approve(int $id)
    {
        $transaction = $this->service->approve($id);

        return new TransactionResource($transaction);
    }

    /**
     * Reject pending borrowing.
     */
    public function reject(int $id)
    {
        $transaction = $this->service->reject($id);

        return new TransactionResource($transaction);
    }

    /**
     * Pickup approved borrowing.
     */
    public function pickup(int $id)
    {
        $transaction = $this->service->pickup($id);

        return new TransactionResource($transaction);
    }

    /**
     * Cancel pending/approved borrowing.
     */
    public function cancel(int $id)
    {
        $transaction = $this->service->cancel($id);

        return new TransactionResource($transaction);
    }

    /**
     * Return a borrowed book.
     */
    public function returnBook(int $id)
    {
        $transaction = $this->service->returnBook($id);

        return new TransactionResource($transaction);
    }

    /**
     * Renew an active borrowing.
     */
    public function renew(int $id)
    {
        $transaction = $this->service->renew($id);

        return new TransactionResource($transaction);
    }

    /**
     * Mark borrowing as lost.
     */
    public function markLost(int $id)
    {
        $transaction = $this->service->markLost($id);

        return new TransactionResource($transaction);
    }

    /**
     * Mark borrowing as overdue.
     */
    public function markOverdue(int $id)
    {
        $transaction = $this->service->markOverdue($id);

        return new TransactionResource($transaction);
    }

    /**
     * Soft delete transaction.
     */
    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Transaction deleted successfully.',
        ]);
    }

    /**
     * Get only trashed transactions.
     */
    public function trashed()
    {
        $transactions = $this->service->getTransactionOnlyTrashed();

        return TransactionResource::collection($transactions);
    }

    /**
     * Restore soft deleted transaction.
     */
    public function restore(int $id)
    {
        $transaction = $this->service->restore($id);

        return new TransactionResource($transaction);
    }

    /**
     * Permanently delete transaction.
     */
    public function forceDelete(int $id)
    {
        $this->service->forceDelete($id);

        return response()->json([
            'message' => 'Transaction permanently deleted successfully.',
        ]);
    }
}

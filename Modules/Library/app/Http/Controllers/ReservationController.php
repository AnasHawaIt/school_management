<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Library\app\Entities\Reservation;
use Modules\Library\app\Services\ReservationService;
use Modules\Library\app\Http\Requests\StoreReservationRequest;

class ReservationController extends Controller
{
    public function __construct(
        protected ReservationService $service
    ) {
    }

    /**
     * Display reservations.
     */
    public function index(Request $request)
    {
        return response()->json(
            $this->service->getAll($request)
        );
    }

    /**
     * Get reservations for a specific book.
     */
    public function byBook(int $bookId)
    {
        return response()->json(
            $this->service->getByBook($bookId)
        );
    }


    /**
     * Get reservations for a specific member.
     */
    public function byMember(int $memberId)
    {
        return response()->json(
            $this->service->getByMember($memberId)
        );
    }


    /**
     * Get member's pending reservation for a book.
     */
    public function memberPending(
        int $bookId,
        int $memberId
    ) {
        $reservation = $this->service->getMemberPendingReservation(
            $bookId,
            $memberId
        );

        if (!$reservation) {
            return response()->json([
                'message' => 'No pending reservation found.',
            ], 404);
        }

        return response()->json(
            $reservation->load(['book', 'member.user'])
        );
    }


    /**
     * Process the next reservation.
     */
    public function processNext(int $bookId)
    {
        $reservation = $this->service->notifyNextMember(
            $bookId
        );

        if (!$reservation) {
            return response()->json([
                'message' => 'There are no pending reservations for this book.',
            ]);
        }

        return response()->json(
            $reservation->load(['book', 'member.user'])
        );
    }


    /**
     * Delete reservation.
     */
    public function destroy(int $reservation)
    {
        $this->service->delete(
            $reservation
        );

        return response()->json([
            'message' => 'Reservation deleted successfully.',
        ]);
    }

    public function show($id)
    {
        return $this->service->find($id);
    }

    /**
     * Create a new reservation.
     */
    public function store(StoreReservationRequest $request)
    {
        $reservation = $this->service->createReservation(
            bookId: $request->integer('book_id'),
            memberId: $request->integer('member_id'),
            data: $request->validated()
        );

        return response()->json(
            $reservation->load(['book', 'member.user']),
            201
        );
    }

    /**
     * Cancel reservation.
     */
    public function cancel(Reservation $reservation)
    {
        $reservation = $this->service->cancelReservation(
            $reservation->id
        );

        return response()->json(
            $reservation->load(['book', 'member.user'])
        );
    }

    /**
     * Fulfill reservation.
     */
    public function fulfill(Reservation $reservation)
    {
        $reservation = $this->service->fulfillReservation(
            $reservation->id
        );

        return response()->json(
            $reservation->load(['book', 'member.user'])
        );
    }

    /**
     * Expire reservation.
     *
     * Intended for admin/system usage.
     */
    public function expire(Reservation $reservation)
    {
        $reservation = $this->service->expireReservation(
            $reservation->id
        );

        return response()->json(
            $reservation->load(['book', 'member.user'])
        );
    }

    /**
     * Notify the next member in FIFO order.
     */
    public function notifyNext(int $bookId)
    {
        $reservation = $this->service->notifyNextMember(
            $bookId
        );

        if (!$reservation) {
            return response()->json([
                'message' => 'There are no pending reservations for this book.',
            ]);
        }

        return response()->json(
            $reservation->load(['book', 'member.user'])
        );
    }
}

<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Library\Entities\Reservation;
use Modules\Library\Services\ReservationService;
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

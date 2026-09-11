<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\Member;
use Modules\Library\Entities\Reservation;
use Modules\Library\app\Http\Requests\StoreReservationRequest;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        return Reservation::with(['book', 'member.user'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(min((int) $request->get('per_page', 10), 100));
    }

    public function store(StoreReservationRequest $request)
    {
        $member = Member::findOrFail($request->integer('member_id'));
        if ($member->membership_status !== 'active') {
            throw ValidationException::withMessages(['member_id' => 'Membership is not active.']);
        }

        $book = Book::findOrFail($request->integer('book_id'));
        if (!($book->copies()
            ->where('status', 'available')
            ->exists())) { //$book->hasAvailableCopies()
            throw ValidationException::withMessages(['book_id' => 'This book is currently available.']);
        }

        $reservation = Reservation::firstOrCreate(
            ['book_id' => $book->id, 'member_id' => $member->id, 'status' => 'pending'],
            ['status' => 'pending']
        );

        return response()->json($reservation->load(['book', 'member.user']), 201);
    }

    public function cancel(Reservation $reservation)
    {
        abort_unless($reservation->status === 'pending', 422, 'Only pending reservations can be cancelled.');
        $reservation->update(['status' => 'cancelled']);

        return response()->json($reservation->refresh());
    }
}

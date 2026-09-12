<?php

namespace Modules\Library\Repositories\Eloquent;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\Member;
use Modules\Library\Entities\Reservation;
use Modules\Library\Repositories\Interfaces\ReservationRepositoryInterface;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function getAll(Request $request): LengthAwarePaginator
    {
        return Reservation::query()
            ->with(['book', 'member.user'])
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where(
                    'status',
                    $request->string('status')
                )
            )
            ->latest()
            ->paginate(
                min(
                    (int) $request->get('per_page', 10),
                    100
                )
            );
    }

    public function create(array $data): Reservation
    {
        return Reservation::create($data);
    }

    public function find(int $id): Reservation
    {
        return Reservation::with([
            'book',
            'member',
        ])->findOrFail($id);
    }

    public function findForUpdate(int $id): Reservation
    {
        return Reservation::query()
            ->with([
                'book',
                'member',
            ])
            ->lockForUpdate()
            ->findOrFail($id);
    }

    public function update(Reservation $reservation, array $data): Reservation
    {
        $reservation->update($data);

        return $reservation->refresh();
    }

    public function delete(Reservation $reservation): bool
    {
        return (bool)$reservation->delete();
    }

    public function getByBook(int $bookId): LengthAwarePaginator
    {
        return Reservation::query()
            ->where('book_id', $bookId)
            ->with('member')
            ->latest('created_at')
            ->paginate(15);
    }

    public function getByMember(int $memberId): LengthAwarePaginator
    {
        return Reservation::query()
            ->where('member_id', $memberId)
            ->with('book')
            ->latest('created_at')
            ->paginate(15);
    }

    /**
     * Get the next pending reservation in FIFO order.
     *
     * The row is locked for update to prevent two concurrent
     * processes/jobs from selecting the same reservation.
     */
    public function getNextPendingReservation(
        int $bookId
    ): ?Reservation {
        return Reservation::query()
            ->where('book_id', $bookId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->lockForUpdate()
            ->first();
    }


    public function findActiveReservation(int $bookId, int $memberId): ?Reservation
    {
        return Reservation::query()
            ->where('book_id', $bookId)
            ->where('member_id', $memberId)
            ->whereIn('status', [
                'pending',
                'notified',
            ])
            ->first();
    }

    public function existsPendingReservation(int $bookId, int $memberId): bool
    {
        return Reservation::query()
            ->where('book_id', $bookId)
            ->where('member_id', $memberId)
            ->where('status', 'pending')
            ->exists();
    }
    /**
     * Get member's pending reservation for a book.
     */
    public function getMemberPendingReservation(int $bookId, int $memberId): ?Reservation
    {
        return Reservation::query()
            ->where('book_id', $bookId)
            ->where('member_id', $memberId)
            ->where('status', 'pending')
            ->first();
    }

    public function cancel(Reservation $reservation): Reservation
    {
        return $this->update($reservation, [
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }

    public function fulfill(Reservation $reservation): Reservation
    {
        return $this->update($reservation, [
            'status' => 'fulfilled',
            'fulfilled_at' => now(),
        ]);
    }

    public function expire(Reservation $reservation): Reservation
    {
        return $this->update($reservation, [
            'status' => 'expired',
            'expired_at' => now(),
        ]);
    }

    public function markAsNotified(Reservation $reservation): Reservation
    {
        return $this->update($reservation, [
            'status' => 'notified',
            'notified_at' => now(),
        ]);
    }

    public function getBook(int $bookId): Book
    {
        return Book::query()
            ->withCount([
                'copies as available_copies_count' => function ($query) {
                    $query->where('status', 'available');
                },
            ])
            ->findOrFail($bookId);
    }

    public function getMember(int $memberId): Member
    {
        return Member::findOrFail($memberId);
    }
}

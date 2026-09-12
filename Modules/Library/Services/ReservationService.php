<?php

namespace Modules\Library\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Library\app\Enums\ReservationStatus;
use Modules\Library\Entities\Reservation;
use Modules\Library\Events\ReservationEvents\ReservationCancelled;
use Modules\Library\Events\ReservationEvents\ReservationCreated;
use Modules\Library\Events\ReservationEvents\ReservationExpired;
use Modules\Library\Events\ReservationEvents\ReservationFulfilled;
use Modules\Library\Events\ReservationEvents\ReservationNotified;
use Modules\Library\Repositories\Interfaces\BookRepositoryInterface;
use Modules\Library\Repositories\Interfaces\ReservationRepositoryInterface;

class ReservationService
{
    public function __construct(
        protected ReservationRepositoryInterface $repository,
        protected BookRepositoryInterface $bookRepository,
    ) {
    }

    public function getAll(Request $request)
    {
        return $this->repository->getAll($request);
    }

    /**
     * Create a new reservation.
     */
    public function createReservation(
        int $bookId,
        int $memberId,
        array $data = []
    ): Reservation {
        return DB::transaction(function () use (
            $bookId,
            $memberId,
            $data
        ) {
            $book = $this->repository->getBook($bookId);

            $this->repository->getMember($memberId);

            if ($this->bookRepository->isAvailable($bookId)) {
                throw new \RuntimeException(
                    'This book is currently available. A reservation is not required.'
                );
            }

            if (
                $this->repository->findActiveReservation(
                    $bookId,
                    $memberId
                )
            ) {
                throw new \RuntimeException(
                    'Member already has an active reservation for this book.'
                );
            }

            $reservationData = array_merge($data, [
                'book_id' => $bookId,
                'member_id' => $memberId,
                'status' => 'pending',
            ]);

            $reservation = $this->repository->create(
                $reservationData
            );

            event(new ReservationCreated($reservation));

            return $reservation;
        });
    }

    /**
     * Cancel reservation.
     */
    public function cancelReservation(
        int $reservationId
    ): Reservation {
        return DB::transaction(function () use ($reservationId) {

            $reservation = $this->repository->findForUpdate(
                $reservationId
            );

            /*
             * Only pending/notified reservations can be cancelled.
             */
            if (
                !in_array(
                    $reservation->status,
                    ['pending', 'notified'],
                    true
                )
            ) {
                throw new \RuntimeException(
                    'Only pending or notified reservations can be cancelled.'
                );
            }

            $reservation = $this->repository->cancel(
                $reservation
            );

            event(new ReservationCancelled($reservation));

            /*
             * Notify the next member in FIFO order.
             */
            $this->notifyNextMember(
                $reservation->book_id
            );

            return $reservation;
        });
    }

    /**
     * Fulfill reservation.
     */
    public function fulfillReservation(
        int $reservationId
    ): Reservation {
        return DB::transaction(function () use ($reservationId) {

            $reservation = $this->repository->findForUpdate(
                $reservationId
            );

            /*
             * Only pending/notified reservations can be fulfilled.
             */
            if (
                !in_array(
                    $reservation->status,
                    ['pending', 'notified'],
                    true
                )
            ) {
                throw new \RuntimeException(
                    'Only pending or notified reservations can be fulfilled.'
                );
            }

            $reservation = $this->repository->fulfill(
                $reservation
            );

            event(new ReservationFulfilled($reservation));

            return $reservation;
        });
    }

    public function cancel(int $id): Reservation
    {
        return DB::transaction(function () use ($id) {

            $reservation = $this->repository->findForUpdate($id);

            if (!in_array($reservation->status, [
                ReservationStatus::PENDING,
                ReservationStatus::NOTIFIED,
            ])) {
                throw new \DomainException(
                    'This reservation cannot be cancelled.'
                );
            }

            $reservation->update([
                'status' => ReservationStatus::CANCELLED,
            ]);

            event(new ReservationCancelled($reservation));

            return $reservation;
        });
    }


    public function fulfill(int $id): Reservation
    {
        return DB::transaction(function () use ($id) {

            $reservation = $this->repository->findForUpdate($id);

            if ($reservation->status !== ReservationStatus::NOTIFIED) {
                throw new \DomainException(
                    'Only notified reservations can be fulfilled.'
                );
            }

            $reservation->update([
                'status' => ReservationStatus::FULFILLED,
                'fulfilled_at' => now(),
            ]);

            event(new ReservationFulfilled($reservation));

            return $reservation;
        });
    }

    /**
     * Expire reservation.
     *
     * Intended for Scheduler / Job.
     */
    public function expireReservation(
        int $reservationId
    ): Reservation {
        return DB::transaction(function () use ($reservationId) {

            $reservation = $this->repository->findForUpdate(
                $reservationId
            );

            /*
             * Only pending/notified reservations can expire.
             */
            if (
                !in_array(
                    $reservation->status,
                    ['pending', 'notified'],
                    true
                )
            ) {
                throw new \RuntimeException(
                    'Only pending or notified reservations can expire.'
                );
            }

            $reservation = $this->repository->expire(
                $reservation
            );

            event(new ReservationExpired($reservation));

            /*
             * Give the next member a chance.
             */
            $this->notifyNextMember(
                $reservation->book_id
            );

            return $reservation;
        });
    }
        /**
     * Notify the next member in FIFO order.
     *
     * The repository locks the selected reservation using
     * lockForUpdate() so concurrent jobs/processes cannot
     * select and notify the same reservation.
     */
    public function notifyNextMember(
        int $bookId
    ): ?Reservation {
        return DB::transaction(function () use ($bookId) {

            /*
             * getNextPendingReservation() uses lockForUpdate().
             *
             * This guarantees that once a pending reservation
             * is selected inside this transaction, another
             * concurrent transaction cannot select the same row
             * for update until this transaction finishes.
             */
            $reservation = $this->repository
                ->getNextPendingReservation($bookId);

            if (!$reservation) {
                return null;
            }

            /*
             * Change pending → notified while the row is locked.
             */
            $reservation = $this->repository->markAsNotified(
                $reservation
            );

            event(new ReservationNotified($reservation));

            return $reservation;
        });
    }

    public function processNextReservation(int $bookId): ?Reservation
    {
        return DB::transaction(function () use ($bookId) {

            $reservation = Reservation::query()
                ->where('book_id', $bookId)
                ->where('status', 'pending')
                ->orderBy('position')
                ->lockForUpdate()
                ->first();

            if (!$reservation) {
                return null;
            }

            $reservation->update([
                'status' => 'notified',
                'notified_at' => now(),
                'expires_at' => now()->addDays(
                    config('library.reservation_expiry_days', 2)
                ),
            ]);

            event(new ReservationNotified($reservation));

            return $reservation->fresh();
        });
    }


}

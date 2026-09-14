<?php

namespace Modules\Library\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\Member;
use Modules\Library\Entities\Reservation;

interface ReservationRepositoryInterface
{
    public function getAll(Request $request): LengthAwarePaginator;

    public function create(array $data): Reservation;

    public function find(int $id): Reservation;

    public function findForUpdate(int $id): Reservation;

    public function fulfill(Reservation $reservation): Reservation;

    public function update(
        Reservation $reservation,
        array $data
    ): Reservation;

    public function delete(int $reservation): bool;

    public function getByBook(
        int $bookId
    ): LengthAwarePaginator;

    public function getByMember(
        int $memberId
    ): LengthAwarePaginator;

    public function findActiveReservation(
        int $bookId,
        int $memberId
    ): ?Reservation;

    public function existsPendingReservation(
        int $bookId,
        int $memberId
    ): bool;

    public function getMemberPendingReservation(
        int $bookId,
        int $memberId
    ): ?Reservation;

    public function getNextPendingReservation(
        int $bookId
    ): ?Reservation;

    public function cancel(
        Reservation $reservation
    ): Reservation;


    public function expire(
        Reservation $reservation
    ): Reservation;

    public function markAsNotified(
        Reservation $reservation
    ): Reservation;

    public function getBook(int $bookId): Book;

    public function getMember(int $memberId): Member;
}

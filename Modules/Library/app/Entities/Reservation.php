<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Library\app\Enums\ReservationStatus;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'library_reservations';

    protected $fillable = [
        'book_id',
        'member_id',
        'status',
        'notified_at',
        'fulfilled_at',
        'cancelled_at',
        'expired_at',
    ];

    protected $casts = [
        'status' => ReservationStatus::class,

        'notified_at' => 'datetime',
        'fulfilled_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === ReservationStatus::PENDING;
    }

    public function isNotified(): bool
    {
        return $this->status === ReservationStatus::NOTIFIED;
    }

    public function isFulfilled(): bool
    {
        return $this->status === ReservationStatus::FULFILLED;
    }

    public function isCancelled(): bool
    {
        return $this->status === ReservationStatus::CANCELLED;
    }

    public function isExpired(): bool
    {
        return $this->status === ReservationStatus::EXPIRED;
    }
}

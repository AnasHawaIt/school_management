<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Library\app\Enums\BorrowingStatus;

class Borrowing extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transactions';

    protected $fillable = [
        'member_id',
        'book_id',
        'copy_id',
        'borrow_date',
        'due_date',
        'renewal_count',
        'max_renewals',
        'return_date',
        'returned_at',
        'status',
    ];

    protected $casts = [
        /*
         * Transaction status is controlled by BorrowingStatus.
         */
        'status' => BorrowingStatus::class,

        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'returned_at' => 'datetime',

        'renewal_count' => 'integer',
        'max_renewals' => 'integer',
    ];

    /**
     * Member who borrowed the book.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Book being borrowed.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Physical copy assigned to this borrowing.
     *
     * Physical Copies are the source of truth for inventory.
     */
    public function copy(): BelongsTo
    {
        return $this->belongsTo(
            BookCopy::class,
            'copy_id'
        );
    }

    /**
     * Fine associated with this borrowing.
     */
    public function fine(): HasOne
    {
        return $this->hasOne(
            Fine::class,
            'transaction_id'
        );
    }

    /**
     * Check whether the borrowing is currently active.
     */
    public function isActive(): bool
    {
        return in_array(
            $this->status,
            [
                BorrowingStatus::BORROWED,
                BorrowingStatus::LATE,
            ],
            true
        );
    }

    /**
     * Check whether the borrowing has been returned.
     */
    public function isReturned(): bool
    {
        return $this->status === BorrowingStatus::RETURNED;
    }

    /**
     * Check whether the borrowing is lost.
     */
    public function isLost(): bool
    {
        return $this->status === BorrowingStatus::LOST;
    }

    /**
     * Check whether the borrowing is pending.
     */
    public function isPending(): bool
    {
        return $this->status === BorrowingStatus::PENDING;
    }

    /**
     * Check whether the borrowing is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === BorrowingStatus::APPROVED;
    }

    /**
     * Check whether the borrowing is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === BorrowingStatus::CANCELLED;
    }

    /**
     * Check whether the borrowing is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === BorrowingStatus::REJECTED;
    }
}

<?php

namespace Modules\Library\app\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Library\app\Enums\BookCopiesStatus;

class BookCopy extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'library_copies';

    protected $fillable = [
        'book_id',
        'barcode',
        'status',
        'location',
        'replacement_cost',
    ];

    protected $casts = [
        'status' => BookCopiesStatus::class,
        'replacement_cost' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    /**
     * The book this physical copy belongs to.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(
            Book::class,
            'book_id'
        );
    }

    /**
     * Borrowing transactions associated with this copy.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(
            Borrowing::class,
            'copy_id'
        );
    }

    /**
     * Active borrowing transaction.
     */
    public function activeTransaction(): ?Borrowing
    {
        return $this->transactions()
            ->whereIn('status', [
                'borrowed',
                'late',
            ])
            ->latest('id')
            ->first();
    }
}

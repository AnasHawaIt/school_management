<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Library\app\Enums\BookCopiesStatus;

class BookCopy extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'library_copies';

    protected $dates = [
        'deleted_at',
    ];

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
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function transactions()
    {
        return $this->hasMany(Borrowing::class, 'copy_id');
    }
}

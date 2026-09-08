<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookCopy extends Model
{
    protected $table = 'library_copies';

    protected $fillable = [
        'book_id',
        'barcode',
        'status',
        'location',
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

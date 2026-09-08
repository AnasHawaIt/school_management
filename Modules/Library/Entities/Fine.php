<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fine extends Model
{
    protected $table = 'library_fines';

    protected $fillable = [
        'transaction_id',
        'amount',
        'status',
        'paid_at',
        'waived_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'waived_at' => 'datetime',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class, 'transaction_id');
    }
}

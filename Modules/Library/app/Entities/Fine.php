<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Entities\User;

class Fine extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'library_fines';

    protected $fillable = [
        'transaction_id',
        'amount',
        'status',
        'paid_at',
        'paid_by',
        'waived_at',
        'waived_by',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'waived_at' => 'datetime',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(
            Borrowing::class,
            'transaction_id'
        );
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'paid_by'
        );
    }

    public function waivedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'waived_by'
        );
    }
}

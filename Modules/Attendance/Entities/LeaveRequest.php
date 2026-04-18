<?php

namespace Modules\Attendance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\User;

class LeaveRequest extends Model
{
    protected $fillable = [
        'requestable_type', 'requestable_id', 'type',
        'from_date', 'to_date', 'reason', 'attachment',
        'status', 'admin_notes', 'reviewed_by', 'reviewed_at', 'created_by',
    ];

    protected $casts = [
        'from_date'   => 'date',
        'to_date'     => 'date',
        'reviewed_at' => 'datetime',
    ];

    // ===================== Relationships =====================

    public function requestable(): MorphTo
    {
        return $this->morphTo();
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ===================== Accessors =====================

    public function getDaysCountAttribute(): int
    {
        return $this->from_date->diffInDays($this->to_date) + 1;
    }

    // ===================== Scopes =====================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}

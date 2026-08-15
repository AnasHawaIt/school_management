<?php


namespace Modules\Activities\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityParticipant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'activity_participants';

    protected $fillable = [
        'activity_id',
        'participant_type',
        'participant_id',
        'role',
        'status',
        'registered_at',
        'confirmed_at',
        'attended_at',
        'notes',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'attended_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function activity(): BelongsTo
    {
        return $this->belongsTo(
            Activity::class,
            'activity_id'
        );
    }

    public function participant(): MorphTo
    {
        return $this->morphTo();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeRegistered(Builder $query): Builder
    {
        return $query->where('status', 'registered');
    }

    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeAttended(Builder $query): Builder
    {
        return $query->where('status', 'attended');
    }

    public function scopeAbsent(Builder $query): Builder
    {
        return $query->where('status', 'absent');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [
            'registered',
            'confirmed',
        ]);
    }
}

<?php


namespace Modules\Activities\app\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academic\app\Entities\Teacher;

class ActivitySupervisor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'activity_supervisors';

    protected $fillable = [
        'activity_id',
        'teacher_id',
        'role',
        'is_primary',
        'notes',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
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

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(
            Teacher::class,
            'teacher_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePrimary(Builder $query): Builder
    {
        return $query->where('is_primary', true);
    }
}

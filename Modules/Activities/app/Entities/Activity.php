<?php


namespace Modules\Activities\app\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'activities';

    protected $fillable = [
        'category_id',
        'created_by',
        'title',
        'title_ar',
        'description',
        'description_ar',
        'location',
        'start_at',
        'end_at',
        'capacity',
        'registration_required',
        'registration_deadline',
        'status',
        'is_featured',
        'notes',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'registration_deadline' => 'datetime',
        'capacity' => 'integer',
        'registration_required' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function participants(): HasMany
    {
        return $this->hasMany(
            ActivityParticipant::class,
            'activity_id'
        );
    }

    public function activeParticipants(): HasMany
    {
        return $this->participants()
            ->whereIn('status', [
                'registered',
                'confirmed',
            ]);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            ActivityCategory::class,
            'category_id'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function supervisors(): HasMany
    {
        return $this->hasMany(
            ActivitySupervisor::class,
            'activity_id'
        );
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(
            ActivityAttachment::class,
            'activity_id'
        );
    }
    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('start_at', '>', now());
    }

    public function scopeOngoing(Builder $query): Builder
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}

<?php

namespace Modules\Announcement\Entities;

use App\Models\Images;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Announcement\Enums\AnnouncementAudience;
use Modules\Announcement\Enums\AnnouncementPriority;
use Modules\Announcement\Enums\AnnouncementStatus;

class Announcement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'created_by',
        'title',
        'body',
        'audience',
        'status',
        'priority',
        'is_pinned',
        'scheduled_at',
        'published_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'audience' => AnnouncementAudience::class,
            'status' => AnnouncementStatus::class,
            'priority' => AnnouncementPriority::class,

            'is_pinned' => 'boolean',

            'scheduled_at' => 'datetime',
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePublished(Builder $query): Builder
    {
        return $query->where(
            'status',
            AnnouncementStatus::PUBLISHED->value
        );
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where(
            'status',
            AnnouncementStatus::SCHEDULED->value
        );
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where(
            'status',
            AnnouncementStatus::EXPIRED->value
        );
    }

    public function scopePinned(Builder $query): Builder
    {
        return $query->where('is_pinned', true);
    }

    public function scopeForAudience(
        Builder $query,
        AnnouncementAudience $audience
    ): Builder {
        return $query->where(function (Builder $query) use ($audience) {
            $query
                ->where(
                    'audience',
                    AnnouncementAudience::ALL->value
                )
                ->orWhere(
                    'audience',
                    $audience->value
                );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | State Checks
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === AnnouncementStatus::DRAFT;
    }

    public function isScheduled(): bool
    {
        return $this->status === AnnouncementStatus::SCHEDULED;
    }

    public function isPublished(): bool
    {
        return $this->status === AnnouncementStatus::PUBLISHED;
    }

    public function isExpired(): bool
    {
        return $this->status === AnnouncementStatus::EXPIRED;
    }

    public function isCancelled(): bool
    {
        return $this->status === AnnouncementStatus::CANCELLED;
    }

    /*
    |--------------------------------------------------------------------------
    | State Transitions
    |--------------------------------------------------------------------------
    */

    public function publish(): bool
    {
        if (
            $this->status !== AnnouncementStatus::DRAFT &&
            $this->status !== AnnouncementStatus::SCHEDULED
        ) {
            return false;
        }

        $this->status = AnnouncementStatus::PUBLISHED;
        $this->published_at = now();

        return $this->save();
    }

    public function schedule(\DateTimeInterface $date): bool
    {
        if (
            $this->status !== AnnouncementStatus::DRAFT
        ) {
            return false;
        }

        $this->status = AnnouncementStatus::SCHEDULED;
        $this->scheduled_at = $date;

        return $this->save();
    }

    public function expire(): bool
    {
        if (
            $this->status !== AnnouncementStatus::PUBLISHED
        ) {
            return false;
        }

        $this->status = AnnouncementStatus::EXPIRED;

        return $this->save();
    }

    public function cancel(): bool
    {
//        if (
//            $this->status !== AnnouncementStatus::DRAFT &&
//            $this->status !== AnnouncementStatus::SCHEDULED &&
//            $this->status !== AnnouncementStatus::PUBLISHED
//        ) {
//            return false;
//        }

        $this->status = AnnouncementStatus::CANCELLED;

        return $this->save();
    }

    public function pin(): bool
    {
        $this->is_pinned = true;

        return $this->save();
    }


    public function unpin(): bool
    {
        $this->is_pinned = false;

        return $this->save();
    }

    public function images()
    {
        return $this->morphMany(Images::class, 'imageable');
    }
}

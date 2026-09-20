<?php

namespace Modules\Academic\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Activities\app\Entities\ActivityParticipant;
use Modules\Core\app\Entities\User;

class Guardian extends Model
{
    use  SoftDeletes;

    protected $table = 'parents';

    protected $fillable = [
        'user_id', 'national_id', 'nationality', 'phone_secondary',
         'address', 'city', 'occupation', 'employer',
        'work_phone', 'education_level', 'status', 'notes',
    ];

    // ========== Relationships ==========

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_parent', 'parent_id', 'student_id')
            ->withPivot('relationship', 'is_primary_contact', 'can_pickup')
            ->withTimestamps();
    }

    // ========== Accessors ==========

    public function getFullNameAttribute(): string
    {
        return "{$this->user?->first_name} {$this->user?->last_name}";
    }

    // ========== Scopes ==========

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function activityParticipations(): MorphMany
    {
        return $this->morphMany(
            ActivityParticipant::class,
            'participant'
        );
    }
}


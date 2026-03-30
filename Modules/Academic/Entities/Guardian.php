<?php

namespace Modules\Academic\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use Modules\Academic\Entities\Student;

class Guardian extends Model
{
    use  SoftDeletes;

    protected $table = 'parents';

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'first_name_ar', 'last_name_ar',
        'gender', 'national_id', 'nationality', 'phone', 'phone_secondary',
        'email', 'address', 'city', 'occupation', 'employer',
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
        return "{$this->first_name} {$this->last_name}";
    }

    // ========== Scopes ==========

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}


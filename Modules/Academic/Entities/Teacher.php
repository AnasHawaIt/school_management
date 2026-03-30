<?php

namespace Modules\Academic\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Teacher extends Model
{
    use  SoftDeletes;

    protected $fillable = [
        'user_id', 'employee_id', 'first_name', 'last_name',
        'first_name_ar', 'last_name_ar', 'gender', 'date_of_birth',
        'national_id', 'nationality', 'phone', 'emergency_contact',
        'address', 'city', 'photo', 'specialization', 'experience_years',
        'joining_date', 'salary', 'contract_type', 'status', 'notes',
    ];

    protected $casts = [
        'date_of_birth'  => 'date',
        'joining_date'   => 'date',
        'salary'         => 'decimal:2',
        'experience_years' => 'integer',
    ];

    protected $hidden = ['salary'];

    // ========== Relationships ==========

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function qualifications(): HasMany
    {
        return $this->hasMany(TeacherQualification::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher')
            ->withPivot('section_id', 'academic_year_id')
            ->withTimestamps();
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class);
    }

    // ========== Accessors ==========

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFullNameArAttribute(): string
    {
        return "{$this->first_name_ar} {$this->last_name_ar}";
    }

    // ========== Scopes ==========

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeBySpecialization($query, string $specialization)
    {
        return $query->where('specialization', $specialization);
    }
}

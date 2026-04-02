<?php

namespace Modules\Academic\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use Modules\School\Entities\Section;
use Modules\School\Entities\AcademicYear;
use Modules\Transport\Entities\Subscription;

class Student extends Model
{
    use  SoftDeletes;

    protected $fillable = [
        'user_id', 'student_id', 'first_name', 'last_name',
        'first_name_ar', 'last_name_ar', 'gender', 'date_of_birth',
        'national_id', 'nationality', 'place_of_birth', 'religion',
        'photo', 'address', 'city', 'phone', 'enrollment_date',
        'current_section_id', 'academic_year_id', 'status',
        'previous_school', 'blood_type', 'notes',
    ];

    protected $casts = [
        'date_of_birth'    => 'date',
        'enrollment_date'  => 'date',
    ];

    // ========== Relationships ==========

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'current_section_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function medicalRecord(): HasOne
    {
        return $this->hasOne(StudentMedicalRecord::class);
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(Guardian::class, 'student_parent', 'student_id', 'parent_id')
            ->withPivot('relationship', 'is_primary_contact', 'can_pickup')
            ->withTimestamps();
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

    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }

    // ========== Scopes ==========

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeBySection($query, int $sectionId)
    {
        return $query->where('current_section_id', $sectionId);
    }

    public function scopeByAcademicYear($query, int $yearId)
    {
        return $query->where('academic_year_id', $yearId);
    }

    // ========== TRANSPORT SUBSCRIPTION  ==========
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

}

<?php

namespace Modules\Academic\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Core\Entities\User;

class Teacher extends Model
{
    use  SoftDeletes;

    protected $fillable = [
        'user_id', 'employee_id',
        'national_id', 'nationality', 'emergency_contact',
        'address', 'city', 'specialization', 'experience_years',
        'joining_date', 'salary', 'contract_type', 'status', 'notes',
    ];

    protected $casts = [
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
        // نستخدم الـ optional helper أو الـ Null Safe Operator لضمان عدم حدوث خطأ
        if (!$this->user) {
            return 'No User Assigned';
        }

        // تأكد أن موديل User لديه accessor اسمه full_name
        // أو قم بدمج الحقول هنا مباشرة
        return "{$this->user->first_name} {$this->user->last_name}";
    }

    public function getFullNameArAttribute(): string
    {
        // نستخدم الـ optional helper أو الـ Null Safe Operator لضمان عدم حدوث خطأ
        if (!$this->user) {
            return 'No User Assigned';
        }

        // تأكد أن موديل User لديه accessor اسمه full_name
        // أو قم بدمج الحقول هنا مباشرة
        return "{$this->user->first_name} {$this->user->last_name}";
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

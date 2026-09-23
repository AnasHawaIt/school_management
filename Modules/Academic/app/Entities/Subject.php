<?php

namespace Modules\Academic\app\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\School\Entities\Grade;

class Subject extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'name', 'name_ar', 'description', 'grade_id',
        'weekly_hours', 'credit_hours', 'pass_mark', 'full_mark',
        'is_mandatory', 'color', 'status',
    ];

    protected $casts = [
        'weekly_hours'  => 'integer',
        'credit_hours'  => 'integer',
        'pass_mark'     => 'decimal:2',
        'full_mark'     => 'decimal:2',
        'is_mandatory'  => 'boolean',
    ];

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'subject_teacher')
            ->withPivot('section_id', 'academic_year_id')
            ->withTimestamps();
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByGrade($query, int $gradeId)
    {
        return $query->where('grade_id', $gradeId);
    }
}

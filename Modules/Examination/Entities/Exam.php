<?php

namespace Modules\Examination\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academic\app\Entities\Subject;
use Modules\Academic\app\Entities\Teacher;
use Modules\School\Entities\AcademicYear;
use Modules\School\Entities\Section;
use Modules\School\Entities\Semester;

class Exam extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'name_ar', 'exam_type_id', 'subject_id', 'section_id',
        'academic_year_id', 'semester_id', 'teacher_id',
        'exam_date', 'start_time', 'end_time', 'room',
        'total_marks', 'pass_marks', 'status', 'instructions',
    ];

    protected $casts = [
        'exam_date'   => 'date',
        'total_marks' => 'decimal:2',
        'pass_marks'  => 'decimal:2',
    ];

    // ===================== Relationships =====================

    public function examType(): BelongsTo
    {
        return $this->belongsTo(ExamType::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function teacher(): BelongsTo
    {
        // الاسم عبر teacher->user
        return $this->belongsTo(Teacher::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    // ===================== Scopes =====================

    public function scopeBySection($query, int $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    public function scopeBySemester($query, int $semesterId)
    {
        return $query->where('semester_id', $semesterId);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}

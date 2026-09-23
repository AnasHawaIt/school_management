<?php

namespace Modules\Examination\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Academic\app\Entities\Student;
use Modules\School\Entities\AcademicYear;
use Modules\School\Entities\Section;
use Modules\School\Entities\Semester;

class ReportCard extends Model
{
    protected $fillable = [
        'student_id', 'section_id', 'academic_year_id', 'semester_id',
        'total_marks', 'obtained_marks', 'percentage', 'grade',
        'rank', 'result', 'teacher_remarks', 'is_published',
    ];

    protected $casts = [
        'total_marks'    => 'decimal:2',
        'obtained_marks' => 'decimal:2',
        'percentage'     => 'decimal:2',
        'is_published'   => 'boolean',
    ];

    // ===================== Relationships =====================

    public function student(): BelongsTo
    {
        // الاسم عبر student->user
        return $this->belongsTo(Student::class);
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

    // ===================== Scopes =====================

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeBySection($query, int $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }
}

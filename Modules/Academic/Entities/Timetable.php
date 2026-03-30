<?php

namespace Modules\Academic\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Academic\Entities\Teacher;
use Modules\School\Entities\Section;
use Modules\School\Entities\AcademicYear;
use Modules\School\Entities\Semester;
use Modules\Academic\Entities\Subject;


class Timetable extends Model
{
    protected $fillable = [
        'section_id', 'subject_id', 'teacher_id', 'academic_year_id',
        'semester_id', 'day_of_week', 'period_number', 'start_time',
        'end_time', 'room_number', 'status',
    ];

    protected $casts = [
        'start_time'    => 'datetime:H:i',
        'end_time'      => 'datetime:H:i',
        'period_number' => 'integer',
    ];

    // ========== Relationships ==========

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    // ========== Scopes ==========

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeBySection($query, int $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    public function scopeByTeacher($query, int $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }
//يمكن يغطي مشكلة ترتيب الايام ابجديا
    public function scopeByDay($query, string $day)
    {
        return $query->where('day_of_week', $day);
    }
}

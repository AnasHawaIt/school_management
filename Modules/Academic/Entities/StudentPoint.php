<?php

namespace Modules\Academic\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\School\Entities\AcademicYear;
use Modules\School\Entities\Semester;
use Modules\Attendance\Entities\StudentAttendance;

class StudentPoint extends Model
{
    protected $fillable = [
        'student_id', 'point_category_id', 'academic_year_id', 'semester_id',
        'type', 'points', 'reason', 'date',
        'given_by_type', 'given_by_id',
        'inspection_program_id', 'student_attendance_id', 'notes',
    ];

    protected $casts = [
        'date'   => 'date',
        'points' => 'integer',
    ];

    // ===================== Relationships =====================

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PointCategory::class, 'point_category_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function inspectionProgram(): BelongsTo
    {
        return $this->belongsTo(InspectionProgram::class);
    }

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(StudentAttendance::class, 'student_attendance_id');
    }

    // الموجه أو المعلم اللي أعطى النقطة
    public function givenBy()
    {
        if ($this->given_by_type === 'counselor') {
            return $this->belongsTo(Counselor::class, 'given_by_id');
        }
        return $this->belongsTo(\Modules\Academic\Entities\Teacher::class, 'given_by_id');
    }

    // ===================== Accessors =====================

    // النقاط الفعلية: سالبة إن كانت negative
    public function getEffectivePointsAttribute(): int
    {
        return $this->type === 'negative' ? -$this->points : $this->points;
    }

    // ===================== Scopes =====================

    public function scopePositive($query)
    {
        return $query->where('type', 'positive');
    }

    public function scopeNegative($query)
    {
        return $query->where('type', 'negative');
    }

    public function scopeByStudent($query, int $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeBySemester($query, int $semesterId)
    {
        return $query->where('semester_id', $semesterId);
    }

    public function scopeByGivenBy($query, string $type, int $id)
    {
        return $query->where('given_by_type', $type)->where('given_by_id', $id);
    }
}

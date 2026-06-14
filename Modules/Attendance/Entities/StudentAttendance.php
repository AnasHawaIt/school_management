<?php

namespace Modules\Attendance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Academic\Entities\Student;
use Modules\School\Entities\Section;
use Modules\School\Entities\AcademicYear;
use Modules\School\Entities\Semester;
use App\Models\User;

/**
 * @method insert(array $records)
 */
class StudentAttendance extends Model
{
    protected $fillable = [
        'student_id', 'section_id', 'academic_year_id', 'semester_id',
        'status_id', 'date', 'check_in_time', 'late_minutes', 'notes', 'recorded_by',
    ];

    protected $casts = [
        'date'         => 'date',
        'late_minutes' => 'integer',
    ];

    // ===================== Relationships =====================

    public function student(): BelongsTo
    {
        // البيانات الشخصية عبر student->user
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

    public function status(): BelongsTo
    {
        return $this->belongsTo(AttendanceStatus::class, 'status_id');
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // ===================== Scopes =====================

    public function scopeByDate($query, string $date)
    {
        return $query->where('date', $date);
    }

    public function scopeBySection($query, int $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    public function scopeByStudent($query, int $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopePresent($query)
    {
        return $query->whereHas('status', fn($q) => $q->where('is_present', true));
    }

    public function scopeAbsent($query)
    {
        return $query->whereHas('status', fn($q) => $q->where('is_present', false));
    }
}

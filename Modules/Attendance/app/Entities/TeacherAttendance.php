<?php

namespace Modules\Attendance\app\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Academic\Entities\Teacher;
use Modules\Core\app\Entities\User;

class TeacherAttendance extends Model
{
    protected $fillable = [
        'teacher_id', 'status_id', 'date',
        'check_in_time', 'check_out_time', 'late_minutes', 'notes', 'recorded_by',
    ];

    protected $casts = [
        'date'         => 'date',
        'late_minutes' => 'integer',
    ];

    // ===================== Relationships =====================

    public function teacher(): BelongsTo
    {
        // البيانات الشخصية عبر teacher->user
        return $this->belongsTo(Teacher::class);
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

    public function scopeByTeacher($query, int $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
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

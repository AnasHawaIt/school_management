<?php

namespace Modules\Examination\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Academic\Entities\Student;
use Modules\Core\app\Entities\User;

class ExamResult extends Model
{
    protected $fillable = [
        'exam_id', 'student_id', 'marks_obtained', 'is_absent', 'remarks', 'entered_by',
    ];

    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'is_absent'      => 'boolean',
    ];

    // ===================== Relationships =====================

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        // الاسم عبر student->user
        return $this->belongsTo(Student::class);
    }

    public function enteredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entered_by');
    }

    // ===================== Accessors =====================

    public function getIsPassedAttribute(): bool
    {
        if ($this->is_absent) return false;
        return $this->marks_obtained >= $this->exam->pass_marks;
    }

    public function getPercentageAttribute(): float
    {
        if (!$this->marks_obtained || !$this->exam->total_marks) return 0;
        return round(($this->marks_obtained / $this->exam->total_marks) * 100, 2);
    }
}

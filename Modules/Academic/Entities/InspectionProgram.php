<?php

namespace Modules\Academic\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\School\Entities\Section;
use Modules\School\Entities\AcademicYear;
use Modules\School\Entities\Semester;
use Modules\Core\Entities\User;

class InspectionProgram extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'title_ar', 'section_id', 'academic_year_id', 'semester_id',
        'inspection_date', 'start_time', 'end_time', 'type',
        'status', 'objectives', 'notes', 'created_by','is_current',
    ];

    protected $casts = [
        'inspection_date' => 'date',
    ];

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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function counselors(): BelongsToMany
    {
        return $this->belongsToMany(Counselor::class, 'inspection_program_counselor')
            ->withPivot('role', 'objectives', 'result')
            ->withTimestamps();
    }

    public function scopeBySection($query, int $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}

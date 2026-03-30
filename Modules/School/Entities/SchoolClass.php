<?php

namespace Modules\School\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'grade_id',
        'academic_year_id',
        'name',
        'max_students',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_students' => 'integer',
    ];

    /**
     * Relationships
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'class_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByGrade($query, int $gradeId)
    {
        return $query->where('grade_id', $gradeId);
    }

    public function scopeByAcademicYear($query, int $academicYearId)
    {
        return $query->where('academic_year_id', $academicYearId);
    }

    /**
     * Helper Methods
     */
    public function getTotalStudents(): int
    {
        return $this->sections->sum('current_students');
    }

    public function hasAvailableSeats(): bool
    {
        return $this->getTotalStudents() < $this->max_students;
    }
}

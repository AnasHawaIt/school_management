<?php

namespace Modules\School\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{

    protected $fillable = [
        'academic_year_id',
        'name',
        'start_date',
        'end_date',
        'type',
        'description',
        'is_recurring',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_recurring' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Scopes
     */
    public function scopeByAcademicYear($query, int $academicYearId)
    {
        return $query->where('academic_year_id', $academicYearId);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now()->toDateString());
    }

    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }

    /**
     * Helper Methods
     */
    public function isActive(): bool
    {
        $now = now()->toDateString();
        return $this->start_date <= $now && $this->end_date >= $now;
    }

    public function getDuration(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}

<?php

namespace Modules\School\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'name',
        'max_students',
        'current_students',
        'room_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_students' => 'integer',
        'current_students' => 'integer',
    ];

    /**
     * Relationships
     */
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByClass($query, int $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeAvailable($query)
    {
        return $query->whereRaw('current_students < max_students');
    }

    /**
     * Helper Methods
     */
    public function hasAvailableSeats(): bool
    {
        return $this->current_students < $this->max_students;
    }

    public function getAvailableSeats(): int
    {
        return max(0, $this->max_students - $this->current_students);
    }

    public function incrementStudents(): void
    {
        $this->increment('current_students');
    }

    public function decrementStudents(): void
    {
        if ($this->current_students > 0) {
            $this->decrement('current_students');
        }
    }
}

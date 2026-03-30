<?php

namespace Modules\School\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_current',
        'is_active',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    public function classes()
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function holidays()
    {
        return $this->hasMany(Holiday::class);
    }

    /**
     * Scopes
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Helper Methods
     */
    public function setCurrent(): void
    {
        // Remove current from all others
        self::where('is_current', true)->update(['is_current' => false]);

        // Set this as current
        $this->update(['is_current' => true]);
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isCurrent(): bool
    {
        return $this->is_current;
    }
}

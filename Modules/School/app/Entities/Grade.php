<?php

namespace Modules\School\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'order',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Relationships
     */
    public function classes()
    {
        return $this->hasMany(SchoolClass::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Helper Methods
     */
    public function isPrimary(): bool
    {
        return $this->level === 'primary';
    }

    public function isMiddle(): bool
    {
        return $this->level === 'middle';
    }

    public function isHigh(): bool
    {
        return $this->level === 'high';
    }
}

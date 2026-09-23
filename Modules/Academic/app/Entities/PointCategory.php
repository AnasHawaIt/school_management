<?php

namespace Modules\Academic\app\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PointCategory extends Model
{
    protected $fillable = [
        'name', 'name_ar', 'type', 'default_points',
        'icon', 'color', 'is_active', 'auto_assign',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'auto_assign'  => 'boolean',
    ];

    public function studentPoints(): HasMany
    {
        return $this->hasMany(StudentPoint::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePositive($query)
    {
        return $query->where('type', 'positive');
    }

    public function scopeNegative($query)
    {
        return $query->where('type', 'negative');
    }
}

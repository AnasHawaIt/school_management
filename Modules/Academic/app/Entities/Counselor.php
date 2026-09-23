<?php

namespace Modules\Academic\app\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\app\Entities\User;
use Modules\School\Entities\Section;

class Counselor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'counselor_id', 'specialization', 'status', 'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'counselor_section')
            ->withPivot('academic_year_id')
            ->withTimestamps();
    }

    public function inspectionPrograms(): BelongsToMany
    {
        return $this->belongsToMany(InspectionProgram::class, 'inspection_program_counselor')
            ->withPivot('role', 'observation', 'result')
            ->withTimestamps();
    }

    public function getFullNameAttribute(): string
    {
        return $this->user->first_name . ' ' . $this->user->last_name;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

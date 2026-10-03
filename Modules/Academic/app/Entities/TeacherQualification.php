<?php

namespace Modules\Academic\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherQualification extends Model
{
    protected $fillable = [
        'teacher_id', 'type', 'title', 'institution',
        'field_of_study', 'year_obtained', 'expiry_date', 'document', 'description',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}

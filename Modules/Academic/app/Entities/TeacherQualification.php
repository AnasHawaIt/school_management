<?php

namespace Modules\Academic\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TeacherQualification extends Model
{
    use LogsActivity;
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('teacher_qualification');
    }
}

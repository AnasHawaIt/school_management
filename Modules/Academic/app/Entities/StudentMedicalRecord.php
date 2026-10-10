<?php

namespace Modules\Academic\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StudentMedicalRecord extends Model
{
    use LogsActivity;
    protected $fillable = [
        'student_id', 'chronic_diseases', 'allergies', 'medications',
        'disabilities', 'special_needs', 'doctor_name', 'doctor_phone',
        'insurance_number', 'insurance_company', 'notes',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('student_medical_record');
    }
}

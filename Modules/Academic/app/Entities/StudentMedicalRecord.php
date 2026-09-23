<?php

namespace Modules\Academic\app\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentMedicalRecord extends Model
{
    protected $fillable = [
        'student_id', 'chronic_diseases', 'allergies', 'medications',
        'disabilities', 'special_needs', 'doctor_name', 'doctor_phone',
        'insurance_number', 'insurance_company', 'notes',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}

<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\School\Entities\AcademicYear;
use Modules\School\Entities\Grade;

class FeeStructure extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'fee_type_id', 'academic_year_id', 'grade_id', 'class_id',
        'amount', 'frequency', 'due_date', 'notes', 'is_active',
    ];

    protected $casts = [
        'amount'     => 'decimal:2',
        'due_date'   => 'date',
        'is_active'  => 'boolean',
    ];

    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function studentFees()
    {
        return $this->hasMany(StudentFee::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForYear($query, int $yearId)
    {
        return $query->where('academic_year_id', $yearId);
    }

    public function scopeForGrade($query, int $gradeId)
    {
        return $query->where(function ($q) use ($gradeId) {
            $q->where('grade_id', $gradeId)->orWhereNull('grade_id');
        });
    }
}

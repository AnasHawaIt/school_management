<?php

namespace Modules\Finance\Entities;

use App\Entities\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\School\Entities\AcademicYear;

class StudentFee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id', 'fee_structure_id', 'academic_year_id', 'discount_id',
        'original_amount', 'discount_amount', 'net_amount',
        'paid_amount', 'remaining_amount', 'status', 'due_date', 'notes',
    ];

    protected $casts = [
        'original_amount'  => 'decimal:2',
        'discount_amount'  => 'decimal:2',
        'net_amount'       => 'decimal:2',
        'paid_amount'      => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'due_date'         => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'unpaid')
            ->where('due_date', '<', now());
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['unpaid', 'partial']);
    }

    public function updatePaymentStatus(): void
    {
        if ($this->remaining_amount <= 0) {
            $this->status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
        } elseif ($this->due_date && $this->due_date->isPast()) {
            $this->status = 'overdue';
        }
        $this->save();
    }
}

<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academic\Entities\Student;
use Modules\Core\Entities\User;
use Modules\School\Entities\AcademicYear;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number', 'student_id', 'academic_year_id', 'issued_by',
        'total_amount', 'discount_amount', 'net_amount',
        'paid_amount', 'remaining_amount', 'status',
        'issue_date', 'due_date', 'notes',
    ];

    protected $casts = [
        'total_amount'     => 'decimal:2',
        'discount_amount'  => 'decimal:2',
        'net_amount'       => 'decimal:2',
        'paid_amount'      => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'issue_date'       => 'date',
        'due_date'         => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function scopeOverdue($query)
    {
        return $query->whereIn('status', ['sent', 'partial'])
            ->where('due_date', '<', now());
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = 'INV-' . date('Y') . '-' . str_pad(
                    (static::withTrashed()->count() + 1), 5, '0', STR_PAD_LEFT
                );
            }
        });
    }
}

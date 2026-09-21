<?php

namespace Modules\Finance\Entities;

use App\Entities\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\app\Entities\User;
use Modules\School\Entities\AcademicYear;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'student_id',
        'academic_year_id',
        'issued_by',
        'total_amount',
        'discount_amount',
        'net_amount',
        'paid_amount',
        'remaining_amount',
        'status',
        'issue_date',
        'due_date',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'issue_date' => 'date',
        'due_date' => 'date',
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

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function scopeOverdue($query)
    {
        return $query
            ->whereIn('status', ['sent', 'partial'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->where('remaining_amount', '>', 0);
    }

    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            if (!$invoice->invoice_number) {
                $year = now()->year;
                $next = (int) static::withTrashed()
                    ->whereYear('created_at', $year)
                    ->count() + 1;

                do {
                    $number = 'INV-' . $year . '-' . str_pad((string) $next, 5, '0', STR_PAD_LEFT);
                    $exists = static::withTrashed()->where('invoice_number', $number)->exists();
                    $next++;
                } while ($exists);

                $invoice->invoice_number = $number;
            }
        });
    }
}

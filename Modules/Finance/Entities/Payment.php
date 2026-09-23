<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academic\app\Entities\Student;
use Modules\Core\app\Entities\User;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'payment_number', 'student_id', 'student_fee_id', 'received_by',
        'amount', 'method', 'status',
        'bank_name', 'transfer_reference', 'transfer_date',
        'transaction_id', 'gateway_response', 'paid_at', 'notes',
    ];

    protected $casts = [
        'amount'        => 'decimal:2',
        'transfer_date' => 'date',
        'paid_at'       => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByMethod($query, string $method)
    {
        return $query->where('method', $method);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($payment) {
            if (empty($payment->payment_number)) {
                $payment->payment_number = 'PAY-' . date('Y') . '-' . str_pad(
                    (static::withTrashed()->count() + 1), 5, '0', STR_PAD_LEFT
                );
            }
        });
    }
}

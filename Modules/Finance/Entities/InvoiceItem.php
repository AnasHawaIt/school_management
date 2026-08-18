<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Finance\Entities\Invoice;
use Modules\Finance\Entities\StudentFee;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'student_fee_id',
        'description',
        'original_amount',
        'discount_amount',
        'net_amount',
    ];

    protected $casts = [
        'original_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class);
    }
}

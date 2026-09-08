<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\Library\Database\Factories\TransactionFactory;

class Borrowing extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'transactions';


    protected $fillable = [
        'member_id',
        'book_id',
        'copy_id',
        'borrow_date',
        'due_date',
        'renewal_count',
        'max_renewals',
        'return_date',
        'returned_at',
        'status'
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'returned_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function copy()
    {
        return $this->belongsTo(BookCopy::class, 'copy_id');
    }

    public function fine()
    {
        return $this->hasOne(Fine::class, 'transaction_id');
    }

    // protected static function newFactory(): TransactionFactory
    // {
    //     // return TransactionFactory::new();
    // }
}

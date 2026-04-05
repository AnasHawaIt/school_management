<?php

namespace Modules\Transport\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Academic\Entities\Student;

// use Modules\Transport\Database\Factories\SubscriptionFactory;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
            'student_id',
            'route_id',
            'start_date',
            'end_date',
            'status'
        ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    // protected static function newFactory(): SubscriptionFactory
    // {
    //     // return SubscriptionFactory::new();
    // }

}

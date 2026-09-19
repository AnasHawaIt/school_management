<?php

namespace Modules\Transport\app\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\Transport\Database\Factories\BusFactory;

class Bus extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'plate_number',
        'status',
        'capacity',
    ];

    public function routes()
    {
        return $this->hasMany(Route::class);
    }

    public function locations()
    {
        return $this->hasMany(BusLocation::class);
    }

    public function trackingStates()
    {
        return $this->hasMany(
            BusTrackingState::class,
            'bus_id'
        );
    }

    // protected static function newFactory(): BusFactory
    // {
    //     // return BusFactory::new();
    // }
}

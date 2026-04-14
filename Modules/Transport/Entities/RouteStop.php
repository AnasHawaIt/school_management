<?php

namespace Modules\Transport\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\Transport\Database\Factories\RouteStopFactory;

class RouteStop extends Model
{
    use HasFactory ,SoftDeletes;

    protected $dates = ['deleted_at'];

     protected $fillable = ['route_id', 'stop_name', 'sequence'];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    // protected static function newFactory(): RouteStopFactory
    // {
    //     // return RouteStopFactory::new();
    // }
}

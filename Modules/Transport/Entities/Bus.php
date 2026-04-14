<?php

namespace Modules\Transport\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\Transport\Database\Factories\BusFactory;

class Bus extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['plate_number', 'capacity'];

    public function routes()
    {
        return $this->hasMany(Route::class);
    }

    // protected static function newFactory(): BusFactory
    // {
    //     // return BusFactory::new();
    // }
}

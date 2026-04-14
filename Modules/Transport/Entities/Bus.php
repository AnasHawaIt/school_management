<?php

namespace Modules\Transport\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Transport\Database\Factories\BusFactory;

class Bus extends Model
{
    use HasFactory;

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

<?php

namespace Modules\Transport\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Transport\Database\Factories\RouteFactory;

class Route extends Model
{
    use HasFactory;

    protected $fillable = ['bus_id', 'name'];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function stops()
    {
        return $this->hasMany(RouteStop::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

}

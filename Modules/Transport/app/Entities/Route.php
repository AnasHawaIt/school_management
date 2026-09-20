<?php

namespace Modules\Transport\app\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\Transport\Database\Factories\RouteFactory;

class Route extends Model
{
    use HasFactory ,SoftDeletes;

    protected $dates = ['deleted_at'];

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

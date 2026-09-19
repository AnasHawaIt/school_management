<?php

namespace Modules\Transport\app\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusTrackingState extends Model
{
    use HasFactory;

    protected $table = 'bus_tracking_states';

    protected $fillable = [
        'bus_id',
        'route_stop_id',
        'stage',
        'last_notified_at',
    ];

    protected $casts = [
        'last_notified_at' => 'datetime',
    ];

    public function bus(): BelongsTo
    {
        return $this->belongsTo(
            Bus::class,
            'bus_id'
        );
    }

    public function routeStop(): BelongsTo
    {
        return $this->belongsTo(
            RouteStop::class,
            'route_stop_id'
        );
    }
}

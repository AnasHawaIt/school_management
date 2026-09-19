<?php

namespace Modules\Activities\app\Entities;

use Modules\Core\app\Entities\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'activity_attachments';

    protected $fillable = [
        'activity_id',
        'uploaded_by',
        'original_name',
        'file_name',
        'file_path',
        'disk',
        'mime_type',
        'file_size',
        'type',
        'title',
        'description',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function activity(): BelongsTo
    {
        return $this->belongsTo(
            Activity::class,
            'activity_id'
        );
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeImages(Builder $query): Builder
    {
        return $query->where('type', 'image');
    }

    public function scopeDocuments(Builder $query): Builder
    {
        return $query->where('type', 'document');
    }

    public function scopeVideos(Builder $query): Builder
    {
        return $query->where('type', 'video');
    }
}

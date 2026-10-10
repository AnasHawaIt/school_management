<?php

namespace Modules\Messagings\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Messagings\database\factories\MessageAttachmentFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MessageAttachment extends Model
{
    use SoftDeletes, HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()->useLogName('message_attachment');
    }
    protected $fillable = [
        'message_id',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'duration',
    ];

    protected function casts(): array
    {
        return [
            'file_path' => 'encrypted',
            'file_name' => 'encrypted',
            'file_size' => 'integer',
            'duration' => 'integer',
        ];
    }

    public function message()
    {
        return $this->belongsTo(
            Message::class,
            'message_id'
        );
    }

    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    protected static function newFactory(): MessageAttachmentFactory
    {
        return MessageAttachmentFactory::new();
    }
}

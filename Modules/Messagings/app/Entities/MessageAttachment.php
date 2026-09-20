<?php

namespace Modules\Messagings\app\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Messagings\database\factories\MessageAttachmentFactory;

class MessageAttachment extends Model
{
    use SoftDeletes, HasFactory;

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

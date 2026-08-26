<?php

namespace Modules\Messaging\Entities;


namespace Modules\Messagings\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class MessageAttachment extends Model
{
    use SoftDeletes,HasFactory;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'message_id',
        'file_name',
        'file_path',
        'mime_type',
        'file_size'
    ];

    protected function casts(): array
    {
        return [
            'file_path' => 'encrypted',
            'file_name' => 'encrypted',

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
}

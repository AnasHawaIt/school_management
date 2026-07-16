<?php

namespace Modules\Messaging\Entities;


namespace Modules\Messagings\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

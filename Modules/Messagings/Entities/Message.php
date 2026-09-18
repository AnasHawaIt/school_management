<?php

namespace Modules\Messagings\Entities;

use App\Models\Images;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\app\Entities\User;
use Modules\Messagings\database\factories\MessageFactory;


class Message extends Model
{
    use SoftDeletes,HasFactory;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'subject',
        'body',
        'type',
        'priority',
    ];

    protected function casts(): array
    {
        return [
            'subject' => 'encrypted',
            'body' => 'encrypted',
            'type' => 'string',
            'priority' => 'string',
        ];
    }

    public function conversation()
    {
        return $this->belongsTo(
            Conversation::class,
            'conversation_id'
        );
    }

    public function sender()
    {
        return $this->belongsTo(
            User::class,
            'sender_id'
        );
    }

    protected static function booted()
    {
        static::deleting(function ($message) {

            $message->recipients()->delete();

        });
    }

    public function recipients()
    {
        return $this->hasMany(MessageRecipient::class);
    }

    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class);
    }

    public function images()
    {
        return $this->morphMany(Images::class, 'imageable');
    }

    public function statistic()
    {
        return $this->hasOne(MessageStatistic::class);
    }

    protected static function newFactory(): MessageFactory
    {
        return MessageFactory::new();
    }
}

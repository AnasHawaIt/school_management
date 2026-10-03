<?php

namespace Modules\Messagings\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Entities\User;


class MessageStatistic extends Model
{
    use SoftDeletes;

    protected $table ='message_statistics';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'message_id',
        'sender_id',
        'read_count',
        'reply_count',
        'forward_count',
        'first_read_at',
        'last_read_at',
    ];

    protected $casts = [
        'first_read_at' => 'datetime',
        'last_read_at'  => 'datetime',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipients()
    {
        return $this->hasMany(MessageRecipient::class);
    }

}

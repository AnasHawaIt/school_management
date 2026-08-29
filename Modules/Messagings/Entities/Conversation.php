<?php

namespace Modules\Messagings\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Entities\User;

class Conversation extends Model
{
    use SoftDeletes,HasFactory;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'type',
        'title',
        'created_by',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function participants(): HasMany
    {
        return $this->hasMany(
            ConversationParticipant::class,
            'conversation_id'
        );
    }

    public function messages(): HasMany
    {
        return $this->hasMany(
            Message::class,
            'conversation_id'
        );
    }

    public function latestMessage()
    {
        return $this->hasOne(
            Message::class,
            'conversation_id'
        )->latestOfMany();
    }

}

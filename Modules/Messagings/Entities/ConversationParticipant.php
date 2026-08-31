<?php

namespace Modules\Messagings\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\User;

class ConversationParticipant extends Model
{

    protected $fillable = [
        'conversation_id',
        'user_id',
        'conversation_role',
        'joined_at',
        'last_read_at',
        'is_muted',
        'is_archived',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'last_read_at' => 'datetime',
        'is_muted' => 'boolean',
        'is_archived' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function conversation()
    {
        return $this->belongsTo(
            Conversation::class,
            'conversation_id'
        );
    }

    public function isOwner(): bool
    {
        return $this->conversation_role === 'owner';
    }

    public function isAdmin(): bool
    {
        return in_array($this->conversation_role, [
            'admin',
            'owner',
        ]);
    }
}



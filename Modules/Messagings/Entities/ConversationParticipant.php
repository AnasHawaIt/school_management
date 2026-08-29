<?php

namespace Modules\Messagings\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Entities\User;

// use Modules\Messagings\Database\Factories\ConversationParticipantFactory;

class ConversationParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'conversation_Role',
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
        return $this->role === 'owner';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [
            'admin',
            'owner',
        ]);
    }

    // protected static function newFactory(): ConversationParticipantFactory
    // {
    //     // return ConversationParticipantFactory::new();
    // }
}

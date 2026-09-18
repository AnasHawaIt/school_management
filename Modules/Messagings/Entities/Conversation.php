<?php

namespace Modules\Messagings\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\app\Entities\User;
use Modules\Messagings\Database\Factories\ConversationFactory;

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

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'conversation_participants',
            'conversation_id',
            'user_id'
        )
            ->withPivot([
                'conversation_role',
                'joined_at',
            ])
            ->withTimestamps();
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

    public function isOwner(int $userId): bool
    {
        return $this->participants()
            ->where('users.id', $userId)
            ->wherePivot('conversation_role', 'owner')
            ->exists();
    }

    public function isParticipant(int $userId): bool
    {
        return $this->participants()
            ->where('users.id', $userId)
            ->exists();
    }

    public function isAdmin(int $userId): bool
    {
        return $this->participants()
            ->where('users.id', $userId)
            ->wherePivot('conversation_role', 'admin')
            ->exists();
    }

    protected static function newFactory(): ConversationFactory
    {
        return ConversationFactory::new();
    }
}

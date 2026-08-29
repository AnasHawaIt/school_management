<?php

namespace Modules\Messagings\Repositories\Eloquent;

use Modules\Core\Entities\User;
use Modules\Messagings\Entities\Conversation;
use Modules\Messagings\Entities\ConversationParticipant;
use Modules\Messagings\Repositories\Interfaces\ConversationRepositoryInterface;

class ConversationRepository implements ConversationRepositoryInterface
{
    protected ConversationParticipant $model;

    public function __construct(ConversationParticipant $model)
    {
        $this->model = $model;
    }

    public function find(int $id): Conversation
    {
        return Conversation::with([
            'participants',
        ])->findOrFail($id);
    }

    public function findForUser(
        int $conversationId,
        int $userId
    ): Conversation {

        return Conversation::query()
            ->whereKey($conversationId)
            ->whereHas('participants', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->with([
                'participants',
                'latestMessage.sender',
            ])
            ->firstOrFail();
    }

    public function getUserConversations(int $userId)
    {
        return Conversation::query()
            ->whereHas('participants', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->with([
                'participants',
                'latestMessage.sender',
            ])
            ->orderByDesc('last_message_at')
            ->paginate(20);
    }

    public function create(array $data): Conversation
    {
        return Conversation::create($data);
    }

    public function addParticipant(
        int $conversationId,
        int $userId
    ): ConversationParticipant {

        return ConversationParticipant::firstOrCreate([
            'conversation_id' => $conversationId,
            'user_id' => $userId,
        ], [
            'joined_at' => now(),
        ]);
    }

    public function promoteToAdmin(
        Conversation $conversation,
        User $user
    ): ConversationParticipant {
        $participant = $conversation->participants()
            ->where('user_id', $user->id)
            ->firstOrFail();

        $participant->update([
            'conversation_Role' => 'admin',
        ]);

        return $participant->refresh();
    }

    public function demoteToMember(
        Conversation $conversation,
        User $user
    ): ConversationParticipant {
        $participant = $conversation->participants()
            ->where('user_id', $user->id)
            ->firstOrFail();

        $participant->update([
            'conversation_Role' => 'member',
        ]);

        return $participant->fresh();
    }

    public function removeParticipant(
        int $conversationId,
        int $userId
    ): bool {

        return ConversationParticipant::query()
                ->where('conversation_id', $conversationId)
                ->where('user_id', $userId)
                ->delete() > 0;
    }

    public function leave(
        int $conversationId,
        int $userId
    ): bool {
        return ConversationParticipant::query()
                ->where('conversation_id', $conversationId)
                ->where('user_id', $userId)
                ->delete() > 0;
    }

    public function existsParticipant(
        int $conversationId,
        int $userId
    ): bool {

        return ConversationParticipant::query()
            ->where('conversation_id', $conversationId)
            ->where('user_id', $userId)
            ->exists();
    }

    public function delete(int $id): bool
    {
        $conversation = Conversation::findOrFail($id);

        return $conversation->delete();
    }
}

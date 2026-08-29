<?php
namespace Modules\Messagings\Repositories\Eloquent;

use Modules\Messagings\Entities\Conversation;
use Modules\Messagings\Entities\ConversationParticipant;

class ConversationRepository
{
    public function find(int $id): Conversation
    {
        return Conversation::with([
            'participants',
        ])->findOrFail($id);
    }

    public function addParticipant(
        int $conversationId,
        int $userId
    ): ConversationParticipant {

        return ConversationParticipant::firstOrCreate([
            'conversation_id' => $conversationId,
            'user_id' => $userId,
        ]);
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

    public function delete(int $id): bool
    {
        $conversation = Conversation::findOrFail($id);

        return $conversation->delete();
    }
}

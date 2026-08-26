<?php


namespace Modules\Messagings\Services;

use Illuminate\Support\Facades\DB;
use Modules\Messagings\Entities\Conversation;

class ConversationService
{
    public function create(array $data, int $userId): Conversation
    {
        return DB::transaction(function () use ($data, $userId) {

            $participantIds = collect($data['participants'])
                ->push($userId)
                ->unique()
                ->values();

            $conversation = Conversation::create([
                'type' => $data['type'],
                'title' => $data['title'] ?? null,
                'created_by' => $userId,
            ]);

            $conversation->participants()->attach(
                $participantIds->mapWithKeys(
                    fn($id) => [
                        $id => [
                            'joined_at' => now(),
                        ],
                    ]
                )->toArray()
            );

            return $conversation->load('participants');
        });
    }

    public function getUserConversations(int $userId)
    {
        return Conversation::query()
            ->whereHas(
                'participants',
                fn($query) => $query->where('users.id', $userId)
            )
            ->with([
                'participants',
                'latestMessage.sender',
            ])
            ->orderByDesc('last_message_at')
            ->paginate(20);
    }

    public function findForUser(
        int $conversationId,
        int $userId
    ): Conversation
    {

        return Conversation::query()
            ->whereKey($conversationId)
            ->whereHas(
                'participants',
                fn($query) => $query->where('users.id', $userId)
            )
            ->with([
                'participants',
                'latestMessage.sender',
            ])
            ->firstOrFail();
    }
}

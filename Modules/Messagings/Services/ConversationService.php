<?php


namespace Modules\Messagings\Services;

use Illuminate\Support\Facades\DB;
use Modules\Messagings\Entities\Conversation;
use Modules\Messagings\Entities\ConversationParticipant;
use Modules\Messagings\Repositories\Eloquent\ConversationRepository;

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

    public function join(int $conversationId, int $userId): Conversation
    {
        return DB::transaction(function () use ($conversationId, $userId) {

            $conversation = Conversation::findOrFail($conversationId);

            // منع الانضمام إلى private بشكل مباشر
            if ($conversation->type === 'private') {
                abort(403, 'You cannot join a private conversation directly.');
            }

            // هل المستخدم موجود أصلًا؟
            $alreadyParticipant = $conversation
                ->participants()
                ->where('users.id', $userId)
                ->exists();

            if ($alreadyParticipant) {
                abort(422, 'You are already a participant in this conversation.');
            }

            $conversation->participants()->attach($userId, [
                'joined_at' => now(),
            ]);

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

    public function __construct(
        protected ConversationRepository $repo
    ) {
    }

    public function find(int $id): Conversation
    {
        return $this->repo->find($id);
    }

    public function addParticipant(
        int $conversationId,
        int $userId
    ): ConversationParticipant {
        return $this->repo->addParticipant(
            $conversationId,
            $userId
        );
    }

    public function removeParticipant(
        int $conversationId,
        int $userId
    ): bool {
        return $this->repo->removeParticipant(
            $conversationId,
            $userId
        );
    }

    public function delete(int $id): bool
    {
        return $this->repo->delete($id);
    }

}

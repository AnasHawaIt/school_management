<?php

namespace Modules\Messagings\Services;

use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\User;
use Modules\Messagings\Entities\Conversation;
use Modules\Messagings\Entities\ConversationParticipant;
use Modules\Messagings\Repositories\Eloquent\ConversationRepository;

class ConversationService
{

    public function __construct(
        protected ConversationRepository $repo
    ) {
    }

    public function create(
        array $data,
        int $userId
    ): Conversation {

        return DB::transaction(function () use ($data, $userId) {

            $participantIds = collect($data['participants'])
                ->unique()
                ->reject(fn ($id) => (int) $id === $userId)
                ->values();

            $conversation = $this->repo->create([
                'type'       => $data['type'],
                'title'      => $data['title'] ?? null,
                'created_by' => $userId,
            ]);

            $conversation->participants()->attach($userId, [
                'conversation_Role' => 'owner',
                'joined_at'          => now(),
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            foreach ($participantIds as $participantId) {
                $this->repo->addParticipant(
                    $conversation->id,
                    $participantId
                );
            }

            return $conversation->load('participants');
        });
    }

    public function join(
        int $conversationId,
        int $userId
    ): Conversation {

        return DB::transaction(function () use (
            $conversationId,
            $userId
        ) {

            $conversation = $this->repo->find($conversationId);

            if ($conversation->type === 'private') {
                abort(
                    403,
                    'You cannot join a private conversation directly.'
                );
            }

            if (
                $this->repo->existsParticipant(
                    $conversationId,
                    $userId
                )
            ) {
                abort(
                    422,
                    'You are already a participant in this conversation.'
                );
            }

            $this->repo->addParticipant(
                $conversationId,
                $userId
            );

            return $conversation->load('participants');
        });
    }

    public function getUserConversations(int $userId)
    {
        return $this->repo->getUserConversations($userId);
    }

    public function find(int $id): Conversation
    {
        return $this->repo->find($id);
    }

    public function findForUser(
        int $conversationId,
        int $userId
    ): Conversation {

        return $this->repo->findForUser(
            $conversationId,
            $userId
        );
    }

    public function addParticipant(
        int $conversationId,
        int $userId
    ): ConversationParticipant {

        return ConversationParticipant::firstOrCreate(
            [
                'conversation_id' => $conversationId,
                'user_id' => $userId,
            ],
            [
                'conversation_Role' => 'member',
                'joined_at' => now(),
            ]
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

    public function addAdmin(
        Conversation $conversation,
        User $user
    ): ConversationParticipant {

        $participant = $conversation->participants()
            ->where('users.id', $user->id)
            ->first();

        if (!$participant) {
            throw new \Exception(
                'User is not a participant in this conversation.'
            );
        }

        if ($participant->pivot->conversation_Role === 'owner') {
            throw new \Exception(
                'Conversation owner cannot be promoted to admin.'
            );
        }

        if ($participant->pivot->conversation_Role === 'admin') {
            throw new \Exception(
                'User is already an admin.'
            );
        }

        return $this->repo->promoteToAdmin(
            $conversation,
            $user
        );
    }

    public function removeAdmin(
        Conversation $conversation,
        User $user
    ): ConversationParticipant {

        $participant = $conversation->participants()
            ->where('users.id', $user->id)
            ->first();

        if (!$participant) {
            throw new \Exception(
                'User is not a participant in this conversation.'
            );
        }

        if ($participant->pivot->conversation_Role !== 'admin') {
            throw new \Exception(
                'User is not an admin.'
            );
        }

        return $this->repo->demoteToMember(
            $conversation,
            $user
        );
    }

    public function leave(
        int $conversationId,
        int $userId
    ): bool {
        return $this->repo->leave(
            $conversationId,
            $userId
        );
    }

    public function delete(int $id): bool
    {
        return $this->repo->delete($id);
    }
}

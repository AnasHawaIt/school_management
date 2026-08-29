<?php

namespace Modules\Messagings\Services;

use Illuminate\Support\Facades\DB;
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
                ->push($userId)
                ->unique()
                ->values();

            $conversation = $this->repo->create([
                'type' => $data['type'],
                'title' => $data['title'] ?? null,
                'created_by' => $userId,
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

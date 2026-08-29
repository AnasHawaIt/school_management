<?php

namespace Modules\Messagings\Repositories\Interfaces;

interface ConversationRepositoryInterface
{
    public function find(int $id);

    public function findForUser(
        int $conversationId,
        int $userId
    );

    public function getUserConversations(int $userId);

    public function create(array $data);

    public function addParticipant(
        int $conversationId,
        int $userId
    );

    public function removeParticipant(
        int $conversationId,
        int $userId
    );

    public function leave(
        int $conversationId,
        int $userId
    ): bool;

    public function delete(int $id);

    public function existsParticipant(
        int $conversationId,
        int $userId
    ): bool;
}

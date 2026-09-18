<?php

namespace Modules\Messagings\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Core\app\Entities\User;
use Modules\Messagings\Entities\Conversation;
use Modules\Messagings\Entities\ConversationParticipant;
use Modules\Messagings\Events\AdminDemoted;
use Modules\Messagings\Events\AdminPromoted;
use Modules\Messagings\Events\ConversationCreated;
use Modules\Messagings\Events\ConversationDeleted;
use Modules\Messagings\Events\ParticipantAdded;
use Modules\Messagings\Events\ParticipantLeft;
use Modules\Messagings\Events\ParticipantRemoved;
use Modules\Messagings\Repositories\Eloquent\ConversationRepository;

class ConversationService
{
    public function __construct(
        protected ConversationRepository $repo
    ) {
    }

    /**
     * Create a new conversation.
     */
    public function create(
        array $data,
        int $userId
    ): Conversation {

        /*
        |--------------------------------------------------------------------------
        | Database Transaction
        |--------------------------------------------------------------------------
        */

        $result = DB::transaction(function () use ($data, $userId) {

            $participantIds = collect($data['participants'] ?? [])
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->reject(fn ($id) => $id === $userId)
                ->values();

            /*
            |--------------------------------------------------------------------------
            | Create Conversation
            |--------------------------------------------------------------------------
            */

            $conversation = $this->repo->create([
                'type'       => $data['type'],
                'title'      => $data['title'] ?? null,
                'created_by' => $userId,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Add Owner
            |--------------------------------------------------------------------------
            */

            $conversation->participants()->attach($userId, [
                'conversation_role' => 'owner',
                'joined_at'         => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Add Initial Participants
            |--------------------------------------------------------------------------
            */

            $addedParticipants = [];

            foreach ($participantIds as $participantId) {

                $participant = $this->addParticipant(
                    conversationId: $conversation->id,
                    userId: $participantId,
                    addedBy: $userId,
                    dispatchEvent: false
                );

                $addedParticipants[] = $participant;
            }

            return [
                'conversation'       => $conversation->load('participants'),
                'added_participants' => $addedParticipants,
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | Dispatch Events AFTER Transaction
        |--------------------------------------------------------------------------
        |
        | مهم:
        | نطلق الأحداث بعد نجاح الـ transaction حتى لا يتم إرسال
        | Notification أو تسجيل Audit Log لعملية تم rollback لها.
        |
        */

        event(new ConversationCreated(
            conversation: $result['conversation'],
            userId: $userId
        ));

        foreach ($result['added_participants'] as $participant) {

            event(new ParticipantAdded(
                conversation: $result['conversation'],
                user: $participant->user,
                addedBy: $userId
            ));
        }

        return $result['conversation'];
    }


    /**
     * Join public/group conversation.
     */
    public function join(
        int $conversationId,
        int $userId
    ): Conversation {

        $result = DB::transaction(function () use (
            $conversationId,
            $userId
        ) {

            $conversation = $this->repo->find(
                $conversationId
            );

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

            $participant = $this->addParticipant(
                conversationId: $conversationId,
                userId: $userId,
                addedBy: $userId,
                dispatchEvent: false
            );

            return [
                'conversation' => $conversation->load('participants'),
                'participant'  => $participant,
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | ParticipantAdded
        |--------------------------------------------------------------------------
        */

        event(new ParticipantAdded(
            conversation: $result['conversation'],
            user: $result['participant']->user,
            addedBy: $userId
        ));

        return $result['conversation'];
    }


    /**
     * Get all conversations for a user.
     */
    public function getUserConversations(int $userId)
    {
        return $this->repo->getUserConversations($userId);
    }


    /**
     * Find conversation.
     */
    public function find(int $id): Conversation
    {
        return $this->repo->find($id);
    }


    /**
     * Find conversation accessible by user.
     */
    public function findForUser(
        int $conversationId,
        int $userId
    ): Conversation {

        return $this->repo->findForUser(
            $conversationId,
            $userId
        );
    }


    /**
     * Add participant.
     *
     * $addedBy = user who performed the action.
     */
    public function addParticipant(
        int $conversationId,
        int $userId,
        ?int $addedBy = null,
        bool $dispatchEvent = true
    ): ConversationParticipant {

        $participant = ConversationParticipant::firstOrCreate(
            [
                'conversation_id' => $conversationId,
                'user_id'         => $userId,
            ],
            [
                'conversation_Role' => 'member',
                'joined_at'         => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Dispatch ParticipantAdded only when actually created
        |--------------------------------------------------------------------------
        */

        if (
            $participant->wasRecentlyCreated &&
            $dispatchEvent
        ) {

            $conversation = $this->repo->find(
                $conversationId
            );

            $participant->load('user');

            event(new ParticipantAdded(
                conversation: $conversation,
                user: $participant->user,
                addedBy: $addedBy ?? auth()->id()
            ));
        }

        return $participant->load('user');
    }


    /**
     * Remove participant from conversation.
     *
     * $removedBy = user who performed the action.
     */
    public function removeParticipant(
        int $conversationId,
        int $userId,
        ?int $removedBy = null
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | Get participant BEFORE deleting
        |--------------------------------------------------------------------------
        */

        $conversation = $this->repo->find(
            $conversationId
        );

        $participant = $conversation->participants()
            ->where('users.id', $userId)
            ->with('user')
            ->first();

        if (!$participant) {
            throw new Exception(
                'User is not a participant in this conversation.'
            );
        }

        $user = $participant->user;

        /*
        |--------------------------------------------------------------------------
        | Owner protection
        |--------------------------------------------------------------------------
        */

        if (
            $participant->pivot->conversation_role === 'owner'
        ) {

            throw new Exception(
                'Conversation owner cannot be removed.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete participant
        |--------------------------------------------------------------------------
        */

        $removed = $this->repo->removeParticipant(
            $conversationId,
            $userId
        );

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        if ($removed) {

            event(new ParticipantRemoved(
                conversation: $conversation,
                user: $user,
                removedBy: $removedBy ?? auth()->id()
            ));
        }

        return $removed;
    }


    /**
     * Promote participant to admin.
     */
    public function addAdmin(
        Conversation $conversation,
        User $user,
        ?int $promotedBy = null
    ): ConversationParticipant {

        $participant = $conversation->participants()
            ->where('users.id', $user->id)
            ->first();

        if (!$participant) {

            throw new Exception(
                'User is not a participant in this conversation.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Owner cannot be promoted
        |--------------------------------------------------------------------------
        */

        if (
            $participant->pivot->conversation_Role === 'owner'
        ) {

            throw new Exception(
                'Conversation owner cannot be promoted to admin.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Already admin
        |--------------------------------------------------------------------------
        */

        if (
            $participant->pivot->conversation_Role === 'admin'
        ) {

            throw new Exception(
                'User is already an admin.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Promote
        |--------------------------------------------------------------------------
        */

        $result = $this->repo->promoteToAdmin(
            $conversation,
            $user
        );

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        event(new AdminPromoted(
            conversation: $conversation,
            user: $user,
            promotedBy: $promotedBy ?? auth()->id()
        ));

        return $result;
    }


    /**
     * Demote admin to member.
     */
    public function removeAdmin(
        Conversation $conversation,
        User $user,
        ?int $demotedBy = null
    ): ConversationParticipant {

        $participant = $conversation->participants()
            ->where('users.id', $user->id)
            ->first();

        if (!$participant) {

            throw new Exception(
                'User is not a participant in this conversation.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | User must be admin
        |--------------------------------------------------------------------------
        */

        if (
            $participant->pivot->conversation_Role !== 'admin'
        ) {

            throw new Exception(
                'User is not an admin.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Demote
        |--------------------------------------------------------------------------
        */

        $result = $this->repo->demoteToMember(
            $conversation,
            $user
        );

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        event(new AdminDemoted(
            conversation: $conversation,
            user: $user,
            demotedBy: $demotedBy ?? auth()->id()
        ));

        return $result;
    }


    /**
     * Leave conversation.
     */
    public function leave(
        int $conversationId,
        int $userId
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | Load data BEFORE removing participant
        |--------------------------------------------------------------------------
        */

        $conversation = $this->repo->find(
            $conversationId
        );

        $participant = $conversation->participants()
            ->where('users.id', $userId)
            ->with('user')
            ->first();

        if (!$participant) {

            throw new Exception(
                'User is not a participant in this conversation.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Owner cannot simply leave
        |--------------------------------------------------------------------------
        */

        if (
            $participant->pivot->conversation_role === 'owner'
        ) {

            throw new Exception(
                'Conversation owner cannot leave the conversation.'
            );
        }


        $user = $participant->user;

        /*
        |--------------------------------------------------------------------------
        | Leave
        |--------------------------------------------------------------------------
        */

        $result = $this->repo->leave(
            $conversationId,
            $userId
        );

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        if ($result) {

            event(new ParticipantLeft(
                conversation: $conversation,
                user: $user
            ));
        }

        return $result;
    }


    /**
     * Delete conversation.
     */
    public function delete(
        int $id,
        ?int $deletedBy = null
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | Load conversation BEFORE deletion
        |--------------------------------------------------------------------------
        */

        $conversation = $this->repo->find($id);

        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $result = $this->repo->delete($id);

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        if ($result) {

            event(new ConversationDeleted(
                conversation: $conversation,
                userId: $deletedBy ?? auth()->id()
            ));
        }

        return $result;
    }
}

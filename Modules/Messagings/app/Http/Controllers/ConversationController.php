<?php

namespace Modules\Messagings\app\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Entities\User;
use Modules\Messagings\Entities\Conversation;
use Modules\Messagings\Services\ConversationService;

class ConversationController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected ConversationService $conversationService
    ) {
    }

    public function addParticipant(
        Request $request,
        int $id
    ) {
        $conversation = $this->conversationService->find($id);

        $this->authorize(
            'addParticipant',
            $conversation
        );

        $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $participant = $this->conversationService->addParticipant(
            $id,
            $request->integer('user_id')
        );

        return response()->json([
            'success' => true,
            'message' => 'Participant added successfully.',
            'data' => $participant,
        ], 201);
    }

    public function removeParticipant(
        int $id,
        int $userId
    ) {
        $conversation = $this->conversationService->find($id);

        $this->authorize(
            'removeParticipant',
            $conversation
        );

        $this->conversationService->removeParticipant(
            $id,
            $userId
        );

        return response()->json([
            'success' => true,
            'message' => 'Participant removed successfully.',
        ]);
    }

    public function leave(
        User $user,
        Conversation $conversation
    ): bool {
        $participant = $conversation->participants()
            ->where('user_id', $user->id)
            ->first();

        if (!$participant) {
            return false;
        }

        if ($participant->role === 'owner') {
            return false;
        }

        return true;
    }

    public function addAdmin(
        Conversation $conversation,
        User $user
    ) {
        $this->authorize('addAdmin', $conversation);

        $participant = $this->conversationService->addAdmin(
            $conversation,
            $user
        );

        return response()->json([
            'message' => 'User has been promoted to admin successfully.',
            'participant' => $participant,
        ]);
    }

    public function removeAdmin(
        Conversation $conversation,
        User $user
    ) {
        $this->authorize('removeAdmin', $conversation);

        $participant = $this->conversationService->removeAdmin(
            $conversation,
            $user
        );

        return response()->json([
            'message' => 'Admin has been demoted to member successfully.',
            'participant' => $participant,
        ]);
    }


    public function delete(int $id)
    {
        $conversation = $this->conversationService->find($id);

        $this->authorize(
            'delete',
            $conversation
        );

        $this->conversationService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Conversation deleted successfully.',
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $conversations = $this->conversationService
            ->getUserConversations(
                $request->user()->id
            );

        return response()->json([
            'success' => true,
            'data' => $conversations,
        ]);
    }

    public function join(
        Request $request,
        int $conversation
    ): JsonResponse {

        $user = $request->user();

        $conversation = $this->conversationService->join(
            $conversation,
            $user->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Joined conversation successfully.',
            'data' => $conversation,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => [
                'required',
                'in:private,group,broadcast',
            ],

            'title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'participants' => [
                'required',
                'array',
                'min:1',
            ],

            'participants.*' => [
                'integer',
                'distinct',
                'exists:users,id',
            ],
        ]);

        $conversation = $this->conversationService->create(
            $validated,
            $request->user()->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Conversation created successfully.',
            'data' => $conversation,
        ], 201);
    }

    public function show(
        Request $request,
        Conversation $conversation
    ): JsonResponse {

        $conversation = $this->conversationService->findForUser(
            $conversation->id,
            $request->user()->id
        );

        return response()->json([
            'success' => true,
            'data' => $conversation,
        ]);
    }
}

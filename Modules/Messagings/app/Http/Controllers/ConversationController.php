<?php

namespace Modules\Messagings\app\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Messagings\Entities\Conversation;
use Modules\Messagings\Services\ConversationService;

class ConversationController extends Controller
{
    public function __construct(
        protected ConversationService $conversationService
    ) {
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

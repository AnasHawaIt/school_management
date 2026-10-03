<?php


namespace Modules\Messagings\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Messagings\Entities\Conversation;
use Modules\Messagings\Events\Message\TypingStarted;
use Modules\Messagings\Events\Message\TypingStopped;

class TypingController extends Controller
{
    public function start(Conversation $conversation): JsonResponse
    {

        abort_unless(
            $conversation->isParticipant(auth()->id()),
            403,
            'You are not a participant in this conversation.'
        );

        event(new TypingStarted(
            conversationId: $conversation->id,
            userId: auth()->id(),
        ));

        return response()->json([
            'success' => true,
            'message' => 'Typing started.',
        ]);
    }

    public function stop(Conversation $conversation): JsonResponse
    {
        $user = auth()->user();

        abort_unless(
            $conversation->isParticipant(auth()->id()),
            403,
            'You are not a participant in this conversation.'
        );

        event(new TypingStopped(
            conversationId: $conversation->id,
            userId:auth()->id(),
        ));

        return response()->json([
            'success' => true,
            'message' => 'Typing stopped.',
        ]);
    }
}

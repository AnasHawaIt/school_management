<?php

namespace Modules\SMS\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function send($to, $message)
    {
        $response = Http::asForm()->post(
            env('ULTRAMSG_API_URL') . '/messages/chat',
            [
                'token' => env('ULTRAMSG_TOKEN'),
                'to' => $to,
                'body' => $message,
            ]
        );

        if ($response->failed()) {
            Log::error('WhatsApp Error: ' . $response->body());
            return false;
        }

        Log::info('WhatsApp Sent: ' . $response->body());
        return true;
    }
}

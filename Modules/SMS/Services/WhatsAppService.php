<?php

namespace Modules\SMS\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    function sendWhatsAppMessage(string $to, string $message): bool
    {
        $params = [
            'token' => env('ULTRAMSG_TOKEN'),
            'to' => $to,
            'body' => $message,
        ];
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => env('ULTRAMSG_API_URL')."/messages/chat",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HTTPHEADER => [
                "content-type: application/x-www-form-urlencoded"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            Log::error("UltraMsg Error: $err");
            return false;
        }

        Log::info("UltraMsg Response: $response");

        Log::info(env('ULTRAMSG_TOKEN'));
        return true;
    }

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

        $data = $response->json();

        Log::info('WhatsApp Response', $data);

        if ($response->failed()) {
            Log::error('WhatsApp HTTP Failed', [
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'data' => $data
            ];
        }

        $isSuccess =
            isset($data['sent']) && $data['sent'] == "true"
            || isset($data['status']) && $data['status'] == 'success';

        return [
            'success' => $isSuccess,
            'data' => $data
        ];
    }
}

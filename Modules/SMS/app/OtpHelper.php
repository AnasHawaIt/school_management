<?php
namespace Modules\SMS\app;

use Illuminate\Support\Facades\Log;

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

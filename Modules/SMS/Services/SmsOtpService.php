<?php

namespace Modules\SMS\Services;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Core\app\Entities\User;
use Modules\SMS\Entities\SmsOtp;

class SmsOtpService
{
    private string $smsUrl;
    private string $smsApiKey;
    private int $otpExpiryMinutes = 10;
    private int $otpLength = 5;

    public function __construct()
    {
        $this->smsUrl = config('sms.traccer.url')
            ?? throw new \Exception('SMS URL is not configured');

        $this->smsApiKey = config('sms.traccer.key')
            ?? throw new \Exception('SMS API Key is not configured');
    }

    public function sendOtp(string $phone): bool
    {
        $recentOtp = SmsOtp::where('phone', $phone)
            ->where('created_at', '>', now()->subMinute())
            ->exists();

        if ($recentOtp) {
            throw new \Exception('Please wait before requesting another OTP');
        }

        SmsOtp::where('phone', $phone)->delete();

        $otp = $this->createOtp($phone);

        $message = sprintf(
            'Your OTP code for %s is: %s. Do not share it with anyone.',
            config('app.name'),
            $otp
        );

        return $this->sendSms($phone, $message)->successful();
    }

    public function verifyOtp(string $phone, string $code): array
    {

        $otp = SmsOtp::query()
            ->where('phone', $phone)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp) {
            throw new Exception('OTP is invalid or expired');
        }

        if ($otp->attempts >= 5) {
            throw new Exception('Too many attempts');
        }

        if (!Hash::check($code, $otp->otp)) {
            $otp->increment('attempts');
            throw new \Exception('OTP is invalid or expired');
        }

        $otp->update(['used' => true]);

        $user = User::query()->where('phone', $phone)->firstOrCreate(['phone' => $phone]);

        return [
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user'  => $user,
        ];
    }

    public function sendMessage(string $phone, string $message): bool
    {
        return $this->sendSms($phone, $message)->successful();
    }

    private function sendSms(string $phone, string $message): Response
    {
        Log::info('[SMS] Sending message', [
            'to' => $phone,
        ]);

        $response = Http::withHeaders([
            'Authorization' => $this->smsApiKey,
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ])->post($this->smsUrl, [
            'to'      => $phone,
            'message' => $message,
        ]);

        Log::info('[SMS] Response', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        return $response;
    }

//    public function sendSms($phone, $message)
//    {
//        return Http::withHeaders([
//            'Authorization' => 'AccessKey ' . env('SMS_API_KEY'),
//        ])->post(env('SMS_API_URL') . '/messages', [
//            'originator' => 'YourApp',
//            'recipients' => [$phone],
//            'body' => $message
//        ]);
//    }

    private function createOtp(string $phone): string
    {
        $otp = (string) random_int(
            10 ** ($this->otpLength - 1),
            (10 ** $this->otpLength) - 1
        );
        SmsOtp::query()->create([
            'phone'     => $phone,
            'otp'       => Hash::make($otp),
            'used'      => false,
            'expires_at'=> now()->addMinutes($this->otpExpiryMinutes),
        ]);

        Log::info('[OTP] OTP created', ['phone' => $phone]);

        return $otp;
    }

}

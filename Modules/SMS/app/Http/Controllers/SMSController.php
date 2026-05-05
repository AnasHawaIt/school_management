<?php

namespace Modules\SMS\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Modules\SMS\Services\SmsOtpService;

class SMSController extends Controller{
    public function send(Request $request, SmsOtpService $otpService): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'regex:/^\+?[0-9]{10,15}$/'],
        ]);

        try {
            $sent = $otpService->sendOtp($data['phone']);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'phone' => [$e->getMessage()],
            ]);
        }


        return response()->json([
            'message' => 'OTP sent successfully',
        ]);
    }

    public function verify(Request $request, SmsOtpService $otpService): JsonResponse
    {
        $data = $request->validate(
            ['phone' => ['required', 'regex:/^\+?[0-9]{10,15}$/'],
        ]);

        try {
            $result = $otpService->verifyOtp(
                $data['phone'],
                $data['otp']
            );

            return response()->json($result);

        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'otp' => [$e->getMessage()],
            ]);
        }
    }
}


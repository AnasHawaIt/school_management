<?php


namespace Modules\Notifications\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FcmTokenController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'fcm_token' => [
                'required',
                'string',
                'max:500',
            ],
        ]);

        $user = $request->user();

        $user->update([
            'fcm_token' => $validated['fcm_token'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'FCM token updated successfully.',
        ]);
    }
}

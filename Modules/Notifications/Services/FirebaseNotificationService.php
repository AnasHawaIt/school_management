<?php

namespace Modules\Notifications\Services;

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Modules\Notifications\Entities\Notification;

class FirebaseNotificationService
{
    public function __construct(
        protected Messaging $messaging
    ) {
    }

    public function sendFirebase(
        string $token,
        string $title,
        string $body,
        array $data = []
    ): bool {
        if (empty($token)) {
            return false;
        }

        try {
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification(
                    FirebaseNotification::create(
                        $title,
                        $body
                    )
                )
                ->withAndroidConfig(
                    AndroidConfig::fromArray([
                        'priority' => 'high',
                        'notification' => [
                            'channel_id' => 'high_importance_channel',
                            'sound' => 'default',
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        ],
                    ])
                )
                ->withData(
                    $this->normalizeData($data)
                );

            $this->messaging->send($message);

            return true;

        } catch (\Throwable $e) {

            Log::error('FCM Send Direct Error', [
                'message' => $e->getMessage(),
                'token' => $token,
            ]);

            return false;
        }
    }

    public function sendToTopic(
        string $topic,
        string $title,
        string $body,
        array $data = []
    ): bool {
        try {
            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification(
                    FirebaseNotification::create(
                        $title,
                        $body
                    )
                )
                ->withAndroidConfig(
                    AndroidConfig::fromArray([
                        'priority' => 'high',
                        'notification' => [
                            'channel_id' => 'high_importance_channel',
                            'sound' => 'default',
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        ],
                    ])
                )
                ->withData(
                    $this->normalizeData($data)
                );

            $this->messaging->send($message);

            Log::info('FCM Notification sent successfully to topic', [
                'topic' => $topic,
            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('FCM Send Topic Error', [
                'message' => $e->getMessage(),
                'topic' => $topic,
            ]);

            return false;
        }
    }

    public function resend(
        int $id,
        ?string $targetToken = null
    ): Notification {
        $notification = Notification::findOrFail($id);

        if ($targetToken) {

            $this->sendFirebase(
                $targetToken,
                $notification->title,
                $notification->body,
                $notification->data ?? []
            );

        } else {

            $this->sendToTopic(
                'all',
                $notification->title,
                $notification->body,
                $notification->data ?? []
            );
        }

        return $notification;
    }

    protected function normalizeData(array $data): array
    {
        $normalized = [];

        foreach ($data as $key => $value) {

            $key = is_numeric($key)
                ? 'key_' . $key
                : (string) $key;

            $normalized[$key] = is_scalar($value)
                ? (string) $value
                : json_encode(
                    $value,
                    JSON_UNESCAPED_UNICODE
                );
        }

        return $normalized;
    }
}

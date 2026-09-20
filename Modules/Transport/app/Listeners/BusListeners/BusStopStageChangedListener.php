<?php

namespace Modules\Transport\app\Listeners\BusListeners;

use Illuminate\Support\Facades\Log;
use Modules\Notifications\app\Services\FirebaseNotificationService;
use Modules\Transport\app\Entities\Subscription;
use Modules\Transport\app\Events\BusEvents\BusStopStageChanged;

class BusStopStageChangedListener
{
    public function __construct(
        protected FirebaseNotificationService $firebase
    ) {
    }

    public function handle(BusStopStageChanged $event): void
    {
        $bus = $event->bus;
        $stop = $event->stop;
        $stage = $event->stage;

        $subscriptions = Subscription::query()
            ->with([
                'student.user',
            ])
            ->where('route_id', $stop->route_id)
            ->where('route_stop_id', $stop->id)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->get();

        if ($subscriptions->isEmpty()) {
            Log::info('No active subscriptions for bus stop.', [
                'bus_id' => $bus->id,
                'route_stop_id' => $stop->id,
            ]);

            return;
        }

        [$title, $body] = $this->getNotificationContent(
            $stage,
            $bus->plate_number,
            $stop->stop_name,
            $event->etaMinutes
        );

        foreach ($subscriptions as $subscription) {

            $student = $subscription->student;

            if (!$student || !$student->user) {
                continue;
            }

            $user = $student->user;

            // مهم: اسم الحقل عندك fcm_token
            $token = $user->fcm_token ?? null;

            if (!$token) {
                Log::warning('User has no FCM token.', [
                    'user_id' => $user->id,
                    'student_id' => $student->id,
                ]);

                continue;
            }

            try {

                $sent = $this->firebase->sendFirebase(
                    $token,
                    $title,
                    $body,
                    [
                        'type' => 'bus_tracking',
                        'bus_id' => (string) $bus->id,
                        'route_id' => (string) $stop->route_id,
                        'route_stop_id' => (string) $stop->id,
                        'stage' => $stage,
                        'distance_meters' => (string) $event->distanceMeters,
                        'eta_minutes' => (string) $event->etaMinutes,
                    ]
                );

                Log::info('Bus tracking notification processed.', [
                    'user_id' => $user->id,
                    'student_id' => $student->id,
                    'bus_id' => $bus->id,
                    'route_stop_id' => $stop->id,
                    'stage' => $stage,
                    'sent' => $sent,
                ]);

            } catch (\Throwable $e) {

                Log::error('Failed to send bus tracking notification.', [
                    'user_id' => $user->id,
                    'student_id' => $student->id,
                    'bus_id' => $bus->id,
                    'route_stop_id' => $stop->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    protected function getNotificationContent(
        string $stage,
        string $plateNumber,
        string $stopName,
        int $etaMinutes
    ): array {
        return match ($stage) {

            'approaching' => [
                'الحافلة تقترب',
                "الحافلة {$plateNumber} تقترب من محطة {$stopName}. متوقع الوصول خلال {$etaMinutes} دقيقة.",
            ],

            'near' => [
                'الحافلة قريبة',
                "الحافلة {$plateNumber} أصبحت قريبة من محطة {$stopName}.",
            ],

            'arriving' => [
                'الحافلة على وشك الوصول',
                "الحافلة {$plateNumber} ستصل إلى محطة {$stopName} خلال {$etaMinutes} دقيقة تقريباً.",
            ],

            'arrived' => [
                'الحافلة وصلت',
                "الحافلة {$plateNumber} وصلت إلى محطة {$stopName}.",
            ],

            default => [
                'تحديث الحافلة',
                "تم تحديث حالة الحافلة {$plateNumber}.",
            ],
        };
    }
}

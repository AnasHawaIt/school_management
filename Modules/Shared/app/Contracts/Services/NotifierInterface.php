<?php

namespace Modules\Shared\Contracts\Services;

/**
 * واجهة الإشعارات المشتركة.
 *
 * أي موديول بحاجة يبلّغ مستخدمين بيعتمد على هالواجهة بدل ما يستدعي Notifications مباشرة.
 * التنفيذ (والـ bind) بيكون بموديول Notifications (المرحلة ج، يوم 13):
 *   $this->app->bind(NotifierInterface::class, NotificationService::class);
 */
interface NotifierInterface
{
    /**
     * @param  int[]  $userIds
     * @param  array<string, mixed>  $data
     */
    public function notify(array $userIds, string $title, string $body, array $data = []): void;
}

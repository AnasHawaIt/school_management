<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Modules\Academic\Entities\Guardian;
use Modules\Academic\Entities\Student;
use Modules\Academic\Entities\Teacher;
use Modules\Activities\app\Entities\Activity;
use Modules\Announcement\app\Entities\Announcement;
use Modules\Core\app\Entities\User;
use Modules\Messagings\app\Entities\Message;
use Modules\Messagings\app\Entities\MessageAttachment;
use Modules\Messagings\app\Policies\MessagePolicy;
use Modules\Notifications\app\Entities\Notification;
use Modules\Transport\app\Entities\Bus;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Message::class => MessagePolicy::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        Relation::enforceMorphMap([
            'student' => Student::class,
            'teacher' => Teacher::class,
            'guardian' => Guardian::class,
            'user' => User::class,
            'notification' => Notification::class,
            'activity'     => Activity::class,
            'bus'          => Bus::class,
            'message' => Message::class,
            'message_attachment' => MessageAttachment::class,
            'announcement' =>Announcement::class,
        ]);
    }
}

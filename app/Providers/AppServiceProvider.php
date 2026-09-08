<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Modules\Academic\Entities\Guardian;
use Modules\Academic\Entities\Student;
use Modules\Academic\Entities\Teacher;
use Modules\Activities\Entities\Activity;
use Modules\Core\Entities\User;
use Modules\Library\Entities\Borrowing;
use Modules\Messagings\app\Policies\MessagePolicy;
use Modules\Messagings\Entities\Message;
use Modules\Messagings\Entities\MessageAttachment;
use Modules\Notifications\Entities\Notification;
use Modules\Transport\Entities\Bus;
use App\Policies\LibraryPolicy;
use Modules\Library\Entities\BookCopy;
use Modules\Library\Entities\Fine;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Message::class => MessagePolicy::class,
        BookCopy::class => LibraryPolicy::class,
        Borrowing::class => LibraryPolicy::class,
        Fine::class => LibraryPolicy::class,
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
            'borrowing' => Borrowing::class,
            'notification' => Notification::class,
            'activity'     => Activity::class,
            'bus'          => Bus::class,
            'message' => Message::class,
            'message_attachment' => MessageAttachment::class,
        ]);
    }
}

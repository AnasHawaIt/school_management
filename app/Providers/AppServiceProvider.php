<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Modules\Academic\Entities\Guardian;
use Modules\Academic\Entities\Student;
use Modules\Academic\Entities\Teacher;
use Modules\Messagings\app\Policies\MessagePolicy;
use Modules\Messagings\Entities\Message;

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
        ]);
    }
}

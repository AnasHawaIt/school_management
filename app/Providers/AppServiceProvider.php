<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Modules\Academic\Entities\Guardian;
use Modules\Academic\Entities\Student;
use Modules\Academic\Entities\Teacher;
use Modules\Activities\Entities\Activity;
use Modules\Announcement\Entities\Announcement;
use Modules\Core\Entities\User;
use Modules\Library\Entities\Author;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\BookCopy;
use Modules\Library\Entities\Borrowing;
use Modules\Library\Entities\Category;
use Modules\Library\Entities\Fine;
use Modules\Library\Entities\Member;
use Modules\Library\Entities\Publishers;
use Modules\Library\Policy\LibraryPolicy;
use Modules\Messagings\app\Policies\MessagePolicy;
use Modules\Messagings\Entities\Message;
use Modules\Messagings\Entities\MessageAttachment;
use Modules\Notifications\Entities\Notification;
use Modules\Transport\Entities\Bus;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Message::class => MessagePolicy::class,
        BookCopy::class => LibraryPolicy::class,
        Borrowing::class => LibraryPolicy::class,
        Fine::class => LibraryPolicy::class,
    ];

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
            'borrowing' => Borrowing::class,
            'author' => Author::class,
            'book' => Book::class,
            'category' => Category::class,
            'member' => Member::class,
            'publishers' => Publishers::class,
            'book_copy' => BookCopy::class,
            'fine' => Fine::class,
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

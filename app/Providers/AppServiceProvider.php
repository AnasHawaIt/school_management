<?php

namespace App\Providers;

use App\Entities\Guardian;
use App\Entities\Student;
use App\Entities\Teacher;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Modules\Activities\app\Entities\Activity;
use Modules\Announcement\Entities\Announcement;
use Modules\Core\app\Entities\User;
use Modules\Library\app\Entities\Author;
use Modules\Library\app\Entities\Book;
use Modules\Library\app\Entities\BookCopy;
use Modules\Library\app\Entities\Borrowing;
use Modules\Library\app\Entities\Category;
use Modules\Library\app\Entities\Fine;
use Modules\Library\app\Entities\Member;
use Modules\Library\app\Entities\Publisher;
use Modules\Library\app\Policy\LibraryPolicy;
use Modules\Messagings\app\Entities\Message;
use Modules\Messagings\app\Entities\MessageAttachment;
use Modules\Messagings\app\Policies\MessagePolicy;
use Modules\Notifications\app\Entities\Notification;
use Modules\Transport\app\Entities\Bus;

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
            'publisher' => Publisher::class,
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

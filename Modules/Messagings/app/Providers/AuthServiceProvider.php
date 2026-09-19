<?php

namespace Modules\Messagings\app\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Messagings\app\Entities\Conversation;
use Modules\Messagings\app\Entities\Message;
use Modules\Messagings\app\Entities\MessageAttachment;
use Modules\Messagings\app\Policies\ConversationPolicy;
use Modules\Messagings\app\Policies\MessageAttachmentPolicy;
use Modules\Messagings\app\Policies\MessagePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::policy(
            Message::class,
            MessagePolicy::class
        );

        Gate::policy(
            MessageAttachment::class,
            MessageAttachmentPolicy::class
        );

        Gate::policy(
            Conversation::class,
            ConversationPolicy::class
        );
    }
}

<?php

namespace Modules\Messagings\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Modules\Messagings\app\Policies\ConversationPolicy;
use Modules\Messagings\app\Policies\MessageAttachmentPolicy;
use Modules\Messagings\Entities\Conversation;
use Modules\Messagings\Entities\Message;
use Modules\Messagings\app\Policies\MessagePolicy;
use Modules\Messagings\Entities\MessageAttachment;

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

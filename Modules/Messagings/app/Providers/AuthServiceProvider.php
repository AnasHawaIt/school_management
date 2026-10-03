<?php

namespace Modules\Messagings\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Messagings\Entities\Conversation;
use Modules\Messagings\Entities\Message;
use Modules\Messagings\Entities\MessageAttachment;
use Modules\Messagings\Policies\ConversationPolicy;
use Modules\Messagings\Policies\MessageAttachmentPolicy;
use Modules\Messagings\Policies\MessagePolicy;

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

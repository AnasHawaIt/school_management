<?php

namespace Modules\Messagings\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Messagings\app\Policies\MessagePolicy;
use Modules\Messagings\Entities\Message;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        Message::class => MessagePolicy::class,];
    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

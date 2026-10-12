<?php

namespace Modules\Shared\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Layer 0 module: pure technical tools, no routes, no migrations, no entities.
 * Bindings of shared contracts (e.g. NotifierInterface) are registered by the
 * module that implements them, never here, so Shared never depends on a higher layer.
 */
class SharedServiceProvider extends ServiceProvider
{
    protected string $name = 'Shared';

    protected string $nameLower = 'shared';

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}

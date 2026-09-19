<?php

namespace Modules\Core\app\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Modules\Core\app\Contracts\Repositories\PermissionRepositoryInterface;
use Modules\Core\app\Contracts\Repositories\UserRepositoryInterface;
use Modules\Core\app\Contracts\Services\PermissionServiceInterface;
use Modules\Core\app\Repositories\PermissionRepository;
use Modules\Core\app\Repositories\UserRepository;
use Modules\Core\app\Services\PermissionService;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class CoreServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Core';

    protected string $nameLower = 'core';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));
        Relation::morphMap([
            'user' => \Modules\Core\app\Entities\User::class,
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);

        $this->registerRepositories();
        $this->registerServices();
    }

    protected function registerRepositories(): void
    {
        // User Repository
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        // Role Repository
        $this->app->bind(
            \Modules\Core\app\Contracts\Repositories\RoleRepositoryInterface::class,
            \Modules\Core\app\Repositories\RoleRepository::class
        );

        // Permission Repository
        $this->app->bind(
            PermissionRepositoryInterface::class,
            PermissionRepository::class
        );

        // ActivityLog Repository
        $this->app->bind(
            \Modules\Core\app\Contracts\Repositories\ActivityLogRepositoryInterface::class,
            \Modules\Core\app\Repositories\ActivityLogRepository::class
        );

        // Setting Repository
        $this->app->bind(
            \Modules\Core\app\Contracts\Repositories\SettingRepositoryInterface::class,
            \Modules\Core\app\Repositories\SettingRepository::class
        );
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->name, 'lang'), $this->nameLower);
            $this->loadJsonTranslationsFrom(module_path($this->name, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $configPath = module_path($this->name, config('modules.paths.generator.config.path'));

        if (is_dir($configPath)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $config = str_replace($configPath.DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $config_key = str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''], $config);
                    $segments = explode('.', $this->nameLower.'.'.$config_key);

                    // Remove duplicated adjacent segments
                    $normalized = [];
                    foreach ($segments as $segment) {
                        if (end($normalized) !== $segment) {
                            $normalized[] = $segment;
                        }
                    }

                    $key = ($config === 'config.php') ? $this->nameLower : implode('.', $normalized);

                    $this->publishes([$file->getPathname() => config_path($config)], 'config');
                    $this->merge_config_from($file->getPathname(), $key);
                }
            }
        }
    }

    /**
     * Merge config from the given path recursively.
     */
    protected function merge_config_from(string $path, string $key): void
    {
        $existing = config($key, []);
        $module_config = require $path;

        config([$key => array_replace_recursive($existing, $module_config)]);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->nameLower);

        Blade::componentNamespace(config('modules.namespace').'\\' . $this->name . '\\View\\Components', $this->nameLower);
    }


    protected function registerServices(): void
    {
        // Auth Service
        $this->app->bind(
            \Modules\Core\app\Contracts\Services\AuthServiceInterface::class,
            \Modules\Core\app\Services\AuthService::class
        );

        // User Service
        $this->app->bind(
            \Modules\Core\app\Contracts\Services\UserServiceInterface::class,
            \Modules\Core\app\Services\UserService::class
        );

        // Role Service
        $this->app->bind(
            \Modules\Core\app\Contracts\Services\RoleServiceInterface::class,
            \Modules\Core\app\Services\RoleService::class
        );

        // Permission Service
        $this->app->bind(
            PermissionServiceInterface::class,
            PermissionService::class
        );

        // Setting Service
        $this->app->bind(
            \Modules\Core\app\Contracts\Services\SettingServiceInterface::class,
            \Modules\Core\app\Services\SettingService::class
        );
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->nameLower)) {
                $paths[] = $path.'/modules/'.$this->nameLower;
            }
        }

        return $paths;
    }
}

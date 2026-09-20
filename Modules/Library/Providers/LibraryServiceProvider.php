<?php

namespace Modules\Library\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Modules\Library\app\Console\Commands\CheckOverdueBorrowings;
use Modules\Library\app\Repositories\Eloquent\AuthorRepository;
use Modules\Library\app\Repositories\Eloquent\BookCopyRepository;
use Modules\Library\app\Repositories\Eloquent\BookRepository;
use Modules\Library\app\Repositories\Eloquent\CategoryRepository;
use Modules\Library\app\Repositories\Eloquent\FineRepository;
use Modules\Library\app\Repositories\Eloquent\MemberRepository;
use Modules\Library\app\Repositories\Eloquent\ReservationRepository;
use Modules\Library\app\Repositories\Eloquent\TransactionRepository;
use Modules\Library\app\Repositories\Interfaces\AuthorRepositoryInterface;
use Modules\Library\app\Repositories\Interfaces\BookCopyRepositoryInterface;
use Modules\Library\app\Repositories\Interfaces\BookRepositoryInterface;
use Modules\Library\app\Repositories\Interfaces\CategoryRepositoryInterface;
use Modules\Library\app\Repositories\Interfaces\FineRepositoryInterface;
use Modules\Library\app\Repositories\Interfaces\MemberRepositoryInterface;
use Modules\Library\app\Repositories\Interfaces\ReservationRepositoryInterface;
use Modules\Library\app\Repositories\Interfaces\TransactionRepositoryInterface;
use Nwidart\Modules\Traits\PathNamespace;

class LibraryServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Library';

    protected string $nameLower = 'library';

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

        $this->loadMigrationsFrom(
            module_path($this->name, 'database/migrations')
        );
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);

        $this->app->bind(
            BookRepositoryInterface::class,
            BookRepository::class
        );

        $this->app->bind(
            BookCopyRepositoryInterface::class,
            BookCopyRepository::class );

        $this->app->bind(
            TransactionRepositoryInterface::class,
            TransactionRepository::class
        );

        $this->app->bind(
            AuthorRepositoryInterface::class,
            AuthorRepository::class
        );

        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        $this->app->bind(
            MemberRepositoryInterface::class,
            MemberRepository::class
        );

        $this->app->bind(
            ReservationRepositoryInterface::class,
            ReservationRepository::class
        );

        $this->app->bind(
            FineRepositoryInterface::class,
            FineRepository::class
        );
    }

    /**
     * Register commands.
     */
    protected function registerCommands(): void
    {
        $this->commands([
            CheckOverdueBorrowings::class,
        ]);
    }

    /**
     * Register command schedules.
     */
    protected function registerCommandSchedules(): void
    {
        $this->app->booted(function (): void {
            $this->app->make(Schedule::class)
                ->command('library:check-overdue')
                ->daily();
        });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path(
            'lang/modules/' . $this->nameLower
        );

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom(
                $langPath,
                $this->nameLower
            );

            $this->loadJsonTranslationsFrom($langPath);

            return;
        }

        $moduleLangPath = module_path(
            $this->name,
            'lang'
        );

        if (is_dir($moduleLangPath)) {
            $this->loadTranslationsFrom(
                $moduleLangPath,
                $this->nameLower
            );

            $this->loadJsonTranslationsFrom(
                $moduleLangPath
            );
        }
    }

    /**
     * Register Library configuration.
     *
     * IMPORTANT:
     * config/config.php belongs to Nwidart module configuration.
     *
     * config/library.php contains the actual Library settings:
     *
     * config('library.max_active_loans_per_member')
     * config('library.loan_days')
     * etc.
     */
    protected function registerConfig(): void
    {
        $libraryConfigPath = module_path(
            $this->name,
            'config/library.php'
        );

        if (is_file($libraryConfigPath)) {
            $this->mergeConfigFrom(
                $libraryConfigPath,
                $this->nameLower
            );

            $this->publishes(
                [
                    $libraryConfigPath => config_path('library.php'),
                ],
                'config'
            );
        }
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path(
            'views/modules/' . $this->nameLower
        );

        $sourcePath = module_path(
            $this->name,
            'resources/views'
        );

        $this->publishes(
            [
                $sourcePath => $viewPath,
            ],
            [
                'views',
                $this->nameLower . '-module-views',
            ]
        );

        $this->loadViewsFrom(
            array_merge(
                $this->getPublishableViewPaths(),
                [$sourcePath]
            ),
            $this->nameLower
        );

        Blade::componentNamespace(
            config('modules.namespace')
            . '\\'
            . $this->name
            . '\\View\\Components',
            $this->nameLower
        );
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    /**
     * Get publishable view paths.
     */
    private function getPublishableViewPaths(): array
    {
        $paths = [];

        foreach (config('view.paths') as $path) {
            $moduleViewPath = $path
                . '/modules/'
                . $this->nameLower;

            if (is_dir($moduleViewPath)) {
                $paths[] = $moduleViewPath;
            }
        }

        return $paths;
    }
}

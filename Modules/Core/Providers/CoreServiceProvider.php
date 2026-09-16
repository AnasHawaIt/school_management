<?php

namespace Modules\Core\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Contracts\Repositories\UserRepositoryInterface;
use Modules\Core\Repositories\UserRepository;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Modules\Core\Observers\DomainActivityObserver;

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
            'user' => \Modules\Core\Entities\User::class,
        ]);

        $this->registerDomainActivityObserver();
    }

    /**
     * Register lifecycle logging for domain models that do not have a
     * module-specific event/log listener. Existing event-driven modules keep
     * their public events and are intentionally not double logged.
     */
    private function registerDomainActivityObserver(): void
    {
        $models = [
            'Modules\\Academic\\Entities\\Counselor',
            'Modules\\Academic\\Entities\\Guardian',
            'Modules\\Academic\\Entities\\InspectionProgram',
            'Modules\\Academic\\Entities\\Student',
            'Modules\\Academic\\Entities\\StudentMedicalRecord',
            'Modules\\Academic\\Entities\\StudentPoint',
            'Modules\\Academic\\Entities\\Subject',
            'Modules\\Academic\\Entities\\Teacher',
            'Modules\\Academic\\Entities\\TeacherQualification',
            'Modules\\Academic\\Entities\\Timetable',
            'Modules\\Attendance\\Entities\\LeaveRequest',
            'Modules\\Attendance\\Entities\\StudentAttendance',
            'Modules\\Attendance\\Entities\\TeacherAttendance',
            'Modules\\Finance\\Entities\\Discount',
            'Modules\\Finance\\Entities\\FeeStructure',
            'Modules\\Finance\\Entities\\FeeType',
            'Modules\\Finance\\Entities\\Invoice',
            'Modules\\Finance\\Entities\\InvoiceItem',
            'Modules\\Finance\\Entities\\Payment',
            'Modules\\Finance\\Entities\\StudentFee',
            'Modules\\School\\Entities\\AcademicYear',
            'Modules\\School\\Entities\\Grade',
            'Modules\\School\\Entities\\Holiday',
            'Modules\\School\\Entities\\SchoolClass',
            'Modules\\School\\Entities\\Semester',
            'Modules\\Transport\\Entities\\BusLocation',
            'Modules\\Transport\\Entities\\BusTrackingState',
            'Modules\\Transport\\Entities\\Images',
            'Modules\\Activities\\Entities\\ActivityCategory',
            'Modules\\Examination\\Entities\\ExamResult',
        ];

        $observer = $this->app->make(DomainActivityObserver::class);

        foreach ($models as $model) {
            if (class_exists($model) && is_subclass_of($model, Model::class)) {
                $model::observe($observer);
            }
        }
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
        $this->app->register(RouteServiceProvider::class);

        $this->registerRepositories();
        $this->registerServices();
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
    protected function registerRepositories(): void
    {
        // User Repository
        $this->app->bind(
            \Modules\Core\Contracts\Repositories\UserRepositoryInterface::class,
            \Modules\Core\Repositories\UserRepository::class
        );

        // Role Repository
        $this->app->bind(
            \Modules\Core\Contracts\Repositories\RoleRepositoryInterface::class,
            \Modules\Core\Repositories\RoleRepository::class
        );

        // Permission Repository
        $this->app->bind(
            \Modules\Core\Contracts\Repositories\PermissionRepositoryInterface::class,
            \Modules\Core\Repositories\PermissionRepository::class
        );

        // ActivityLog Repository
        $this->app->bind(
            \Modules\Core\Contracts\Repositories\ActivityLogRepositoryInterface::class,
            \Modules\Core\Repositories\ActivityLogRepository::class
        );

        // Setting Repository
        $this->app->bind(
            \Modules\Core\Contracts\Repositories\SettingRepositoryInterface::class,
            \Modules\Core\Repositories\SettingRepository::class
        );
    }


    protected function registerServices(): void
    {
        // Auth Services
        $this->app->bind(
            \Modules\Core\Contracts\Services\AuthServiceInterface::class,
            \Modules\Core\Services\AuthService::class
        );

        // User Services
        $this->app->bind(
            \Modules\Core\Contracts\Services\UserServiceInterface::class,
            \Modules\Core\Services\UserService::class
        );

        // Role Services
        $this->app->bind(
            \Modules\Core\Contracts\Services\RoleServiceInterface::class,
            \Modules\Core\Services\RoleService::class
        );

        // Setting Services
        $this->app->bind(
            \Modules\Core\Contracts\Services\SettingServiceInterface::class,
            \Modules\Core\Services\SettingService::class
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

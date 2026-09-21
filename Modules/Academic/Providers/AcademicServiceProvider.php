<?php

namespace Modules\Academic\Providers;

use App\Contracts\Repositories\GuardianRepositoryInterface;
use App\Contracts\Repositories\InspectionProgramRepositoryInterface;
use App\Contracts\Repositories\StudentPointRepositoryInterface;
use App\Contracts\Repositories\StudentRepositoryInterface;
use App\Contracts\Repositories\SubjectRepositoryInterface;
use App\Contracts\Repositories\TeacherRepositoryInterface;
use App\Contracts\Repositories\TimetableRepositoryInterface;
use App\Contracts\Services\CounselorServiceInterface;
use App\Contracts\Services\GuardianServiceInterface;
use App\Contracts\Services\InspectionProgramServiceInterface;
use App\Contracts\Services\StudentServiceInterface;
use App\Contracts\Services\SubjectServiceInterface;
use App\Contracts\Services\TeacherServiceInterface;
use App\Contracts\Services\TimetableServiceInterface;
use app\Repositories\CounselorRepository;
use app\Repositories\GuardianRepository;
use app\Repositories\InspectionProgramRepository;
use app\Repositories\StudentPointRepository;
use app\Repositories\StudentRepository;
use app\Repositories\SubjectRepository;
use app\Repositories\TeacherRepository;
use app\Repositories\TimetableRepository;
use app\Services\CounselorService;
use app\Services\GuardianService;
use app\Services\InspectionProgramService;
use app\Services\StudentPointService;
use app\Services\StudentService;
use app\Services\SubjectService;
use app\Services\TeacherService;
use app\Services\TimetableService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Modules\Academic\app\Contracts\Repositories\CounselorRepositoryInterface;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

// Repository Contracts

// Repository Implementations

// Service Contracts

// Service Implementations

class AcademicServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Academic';

    protected string $nameLower = 'academic';

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
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);

        // ===== Repositories =====
        $this->app->bind(TeacherRepositoryInterface::class,           TeacherRepository::class);
        $this->app->bind(StudentRepositoryInterface::class,           StudentRepository::class);
        $this->app->bind(GuardianRepositoryInterface::class,          GuardianRepository::class);
        $this->app->bind(SubjectRepositoryInterface::class,           SubjectRepository::class);
        $this->app->bind(TimetableRepositoryInterface::class,         TimetableRepository::class);
        $this->app->bind(CounselorRepositoryInterface::class,         CounselorRepository::class);
        $this->app->bind(InspectionProgramRepositoryInterface::class, InspectionProgramRepository::class);
        $this->app->bind(StudentPointRepositoryInterface::class,      StudentPointRepository::class);

        // ===== Services =====
        $this->app->bind(TeacherServiceInterface::class,           TeacherService::class);
        $this->app->bind(StudentServiceInterface::class,           StudentService::class);
        $this->app->bind(GuardianServiceInterface::class,          GuardianService::class);
        $this->app->bind(SubjectServiceInterface::class,           SubjectService::class);
        $this->app->bind(TimetableServiceInterface::class,         TimetableService::class);
        $this->app->bind(CounselorServiceInterface::class,         CounselorService::class);
        $this->app->bind(InspectionProgramServiceInterface::class, InspectionProgramService::class);

        // StudentPointService لا يملك Interface — يُحقن مباشرة
        $this->app->bind(StudentPointService::class, StudentPointService::class);
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
        $langPath = resource_path('lang/modules/' . $this->nameLower);

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
                    $config     = str_replace($configPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $config_key = str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''], $config);
                    $segments   = explode('.', $this->nameLower . '.' . $config_key);

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
        $existing      = config($key, []);
        $module_config = require $path;

        config([$key => array_replace_recursive($existing, $module_config)]);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath   = resource_path('views/modules/' . $this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->nameLower);

        Blade::componentNamespace(config('modules.namespace') . '\\' . $this->name . '\\View\\Components', $this->nameLower);
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
            if (is_dir($path . '/modules/' . $this->nameLower)) {
                $paths[] = $path . '/modules/' . $this->nameLower;
            }
        }

        return $paths;
    }
}

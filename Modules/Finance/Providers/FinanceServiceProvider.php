<?php

namespace Modules\Finance\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

// Repository Contracts
use Modules\Finance\Contracts\Repositories\FeeTypeRepositoryInterface;
use Modules\Finance\Contracts\Repositories\FeeStructureRepositoryInterface;
use Modules\Finance\Contracts\Repositories\DiscountRepositoryInterface;
use Modules\Finance\Contracts\Repositories\StudentFeeRepositoryInterface;
use Modules\Finance\Contracts\Repositories\PaymentRepositoryInterface;
use Modules\Finance\Contracts\Repositories\InvoiceRepositoryInterface;

// Repository Implementations
use Modules\Finance\Repositories\FeeTypeRepository;
use Modules\Finance\Repositories\FeeStructureRepository;
use Modules\Finance\Repositories\DiscountRepository;
use Modules\Finance\Repositories\StudentFeeRepository;
use Modules\Finance\Repositories\PaymentRepository;
use Modules\Finance\Repositories\InvoiceRepository;

// Service Contracts
use Modules\Finance\Contracts\Services\FeeTypeServiceInterface;
use Modules\Finance\Contracts\Services\FeeStructureServiceInterface;
use Modules\Finance\Contracts\Services\DiscountServiceInterface;
use Modules\Finance\Contracts\Services\StudentFeeServiceInterface;
use Modules\Finance\Contracts\Services\PaymentServiceInterface;
use Modules\Finance\Contracts\Services\InvoiceServiceInterface;
use Modules\Finance\Contracts\Services\FinanceReportServiceInterface;

// Service Implementations
use Modules\Finance\Services\FeeTypeService;
use Modules\Finance\Services\FeeStructureService;
use Modules\Finance\Services\DiscountService;
use Modules\Finance\Services\StudentFeeService;
use Modules\Finance\Services\PaymentService;
use Modules\Finance\Services\InvoiceService;
use Modules\Finance\Services\FinanceReportService;

class FinanceServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name      = 'Finance';
    protected string $nameLower = 'finance';

    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->registerRepositories();
        $this->registerServices();
    }

    protected function registerRepositories(): void
    {
        $this->app->bind(FeeTypeRepositoryInterface::class,      FeeTypeRepository::class);
        $this->app->bind(FeeStructureRepositoryInterface::class, FeeStructureRepository::class);
        $this->app->bind(DiscountRepositoryInterface::class,     DiscountRepository::class);
        $this->app->bind(StudentFeeRepositoryInterface::class,   StudentFeeRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class,      PaymentRepository::class);
        $this->app->bind(InvoiceRepositoryInterface::class,      InvoiceRepository::class);
    }

    protected function registerServices(): void
    {

        $this->app->bind(FeeTypeServiceInterface::class,       FeeTypeService::class);
        $this->app->bind(FeeStructureServiceInterface::class,  FeeStructureService::class);
        $this->app->bind(DiscountServiceInterface::class,      DiscountService::class);
        $this->app->bind(StudentFeeServiceInterface::class,    StudentFeeService::class);
        $this->app->bind(PaymentServiceInterface::class,       PaymentService::class);
        $this->app->bind(InvoiceServiceInterface::class,       InvoiceService::class);
        $this->app->bind(FinanceReportServiceInterface::class, FinanceReportService::class);
    }

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
                        if (end($normalized) !== $segment) $normalized[] = $segment;
                    }
                    $key = ($config === 'config.php') ? $this->nameLower : implode('.', $normalized);
                    $this->publishes([$file->getPathname() => config_path($config)], 'config');
                    $existing      = config($key, []);
                    $module_config = require $file->getPathname();
                    config([$key => array_replace_recursive($existing, $module_config)]);
                }
            }
        }
    }

    public function registerViews(): void
    {
        $viewPath   = resource_path('views/modules/' . $this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');
        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower . '-module-views']);
        $this->loadViewsFrom([$sourcePath], $this->nameLower);
        Blade::componentNamespace(
            config('modules.namespace') . '\\' . $this->name . '\\View\\Components',
            $this->nameLower
        );
    }

    public function provides(): array { return []; }
}

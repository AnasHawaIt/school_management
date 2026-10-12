<?php

/**
 * حارس: الكلاسات الأساسية انتقلت من app/ إلى Modules\Shared (المرحلة ب، يوم 7 و8).
 * فحص نصي (مو arch()) لأنو arch() ما بيلتقط استيراد كلاس صار محذوف أصلاً.
 */

const LEGACY_SHARED_IMPORTS = [
    'App\\Repositories\\BaseRepository',
    'App\\Contracts\\Repositories\\BaseRepositoryInterface',
    'App\\Filters\\BaseFilter',
    'App\\Filters\\QueryFilter',
    'App\\Models\\Images',
    'App\\Services\\ImageService',
];

it('never imports the legacy base repository / filter classes from app/', function () {
    $root = dirname(__DIR__, 2);
    $hits = [];

    foreach (['Modules', 'app', 'routes', 'database', 'tests'] as $dir) {
        if (!is_dir("$root/$dir")) continue;

        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator("$root/$dir", FilesystemIterator::SKIP_DOTS)
        );
        foreach ($it as $file) {
            if (!$file->isFile() || strtolower($file->getExtension()) !== 'php') continue;
            $path = $file->getPathname();
            if (str_contains($path, DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR)) continue;
            if (str_contains($path, DIRECTORY_SEPARATOR.'node_modules'.DIRECTORY_SEPARATOR)) continue;
            if (basename($path) === 'SharedMigrationGuardTest.php') continue;

            $content = file_get_contents($path);
            foreach (LEGACY_SHARED_IMPORTS as $legacy) {
                if (preg_match('/' . preg_quote($legacy, '/') . '\b/', $content)) {
                    $hits[] = $path . '  ←  ' . $legacy;
                }
            }
        }
    }

    expect($hits)->toBeEmpty(
        "لقيت استيراد قديم لازم يصير Modules\\Shared\\...:\n" . implode("\n", $hits)
    );
});

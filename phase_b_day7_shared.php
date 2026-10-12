<?php

/**
 * المرحلة ب - اليوم 7: نقل BaseRepository / BaseRepositoryInterface / BaseFilter / QueryFilter
 * من app/ إلى Modules/Shared.
 *
 * الاستخدام (من جذر المشروع):
 *   php phase_b_day7_shared.php --dry-run   (يعرض شو رح يتغير بدون ما يعدّل شي)
 *   php phase_b_day7_shared.php             (ينفّذ فعلياً)
 *
 * شرط: مجلد Modules/Shared لازم يكون موجود (من الـ zip) قبل التشغيل.
 */

$root = __DIR__;
$dry = in_array('--dry-run', $argv, true);

if (!is_file("$root/Modules/Shared/app/Repositories/BaseRepository.php")) {
    fwrite(STDERR, "ERROR: Modules/Shared غير موجود. فك الـ zip بجذر المشروع أولاً.\n");
    exit(1);
}

// القديم => الجديد (الترتيب مهم: الـ Interface قبل BaseRepository، والـ \b تمنع التداخل)
$map = [
    '/App\\\\Contracts\\\\Repositories\\\\BaseRepositoryInterface\b/' => 'Modules\\Shared\\Contracts\\Repositories\\BaseRepositoryInterface',
    '/App\\\\Repositories\\\\BaseRepository\b/'                       => 'Modules\\Shared\\Repositories\\BaseRepository',
    '/App\\\\Filters\\\\QueryFilter\b/'                               => 'Modules\\Shared\\Filters\\QueryFilter',
    '/App\\\\Filters\\\\BaseFilter\b/'                                => 'Modules\\Shared\\Filters\\BaseFilter',
];

$oldFiles = [
    'app/Contracts/Repositories/BaseRepositoryInterface.php',
    'app/Repositories/BaseRepository.php',
    'app/Filters/BaseFilter.php',
    'app/Filters/QueryFilter.php',
];

$scanDirs = ['Modules', 'app', 'routes', 'database', 'config', 'tests', 'bootstrap'];
$changed = [];
$total = 0;

foreach ($scanDirs as $dir) {
    if (!is_dir("$root/$dir")) continue;
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("$root/$dir", FilesystemIterator::SKIP_DOTS));
    foreach ($it as $file) {
        if (!$file->isFile() || strtolower($file->getExtension()) !== 'php') continue;
        $path = str_replace('\\', '/', $file->getPathname());
        if (str_contains($path, '/node_modules/') || str_contains($path, '/vendor/')) continue;
        if (str_starts_with($path, "$root/Modules/Shared/")) continue; // الملفات الجديدة جاهزة
        $rel = ltrim(substr($path, strlen($root)), '/');
        if (in_array($rel, $oldFiles, true)) continue;                  // رح تنحذف

        $content = file_get_contents($path);
        $new = $content;
        $count = 0;
        foreach ($map as $pattern => $replacement) {
            $new = preg_replace($pattern, str_replace('\\', '\\\\', $replacement), $new, -1, $n);
            $count += $n;
        }
        if ($count > 0) {
            $changed[$rel] = $count;
            $total += $count;
            if (!$dry) file_put_contents($path, $new);
        }
    }
}

echo ($dry ? "[DRY-RUN] " : '') . "استبدال $total استيراد بـ " . count($changed) . " ملف:\n";
foreach ($changed as $f => $c) echo "  - $f ($c)\n";

// حذف الملفات القديمة + المجلدات الفاضية
foreach ($oldFiles as $f) {
    if (is_file("$root/$f")) {
        echo ($dry ? "[DRY-RUN] " : '') . "حذف $f\n";
        if (!$dry) unlink("$root/$f");
    }
}
if (!$dry) {
    foreach (['app/Contracts/Repositories', 'app/Contracts', 'app/Repositories', 'app/Filters'] as $d) {
        if (is_dir("$root/$d") && count(scandir("$root/$d")) === 2) rmdir("$root/$d");
    }
}

// composer.json: إضافة psr-4 لـ Shared
$composerPath = "$root/composer.json";
$composer = file_get_contents($composerPath);
if (!str_contains($composer, 'Modules\\\\Shared\\\\')) {
    $needle = '"Modules\\\\School\\\\": "Modules/School/app/",';
    if (!str_contains($composer, $needle)) {
        fwrite(STDERR, "WARNING: ما لقيت سطر School بـ composer.json، ضيف يدوياً: \"Modules\\\\Shared\\\\\": \"Modules/Shared/app/\",\n");
    } else {
        echo ($dry ? "[DRY-RUN] " : '') . "composer.json: إضافة Modules\\Shared\n";
        if (!$dry) {
            $composer = str_replace($needle, $needle . "\n            \"Modules\\\\Shared\\\\\": \"Modules/Shared/app/\",", $composer);
            file_put_contents($composerPath, $composer);
        }
    }
}

// modules_statuses.json: Shared أول موديول
$statusPath = "$root/modules_statuses.json";
$status = json_decode(file_get_contents($statusPath), true);
if (!isset($status['Shared'])) {
    echo ($dry ? "[DRY-RUN] " : '') . "modules_statuses.json: إضافة Shared\n";
    if (!$dry) {
        $status = ['Shared' => true] + $status;
        file_put_contents($statusPath, json_encode($status, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
    }
}

echo "\nالخطوة الجاية: composer dump-autoload ; php artisan optimize:clear ; vendor\\bin\\pest tests\\Architecture\n";

<?php

/**
 * المرحلة ب - اليوم 8: نقل ImageService و Images من app/ إلى Modules/Shared.
 *
 * الاستخدام (من جذر المشروع):
 *   php phase_b_day8_shared.php --dry-run
 *   php phase_b_day8_shared.php
 *
 * شرط: فك الـ zip أولاً (بيضيف ملفات Shared الجديدة).
 */

$root = __DIR__;
$dry = in_array('--dry-run', $argv, true);

if (!is_file("$root/Modules/Shared/app/Services/ImageService.php")
    || !is_file("$root/Modules/Shared/app/Entities/Images.php")) {
    fwrite(STDERR, "ERROR: ملفات Shared الجديدة غير موجودة. فك الـ zip بجذر المشروع أولاً.\n");
    exit(1);
}

$map = [
    '/App\\\\Models\\\\Images\b/'        => 'Modules\\Shared\\Entities\\Images',
    '/App\\\\Services\\\\ImageService\b/' => 'Modules\\Shared\\Services\\ImageService',
];
$oldFiles = ['app/Models/Images.php', 'app/Services/ImageService.php'];

$changed = [];
$total = 0;
foreach (['Modules', 'app', 'routes', 'database', 'config', 'tests', 'bootstrap'] as $dir) {
    if (!is_dir("$root/$dir")) continue;
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("$root/$dir", FilesystemIterator::SKIP_DOTS));
    foreach ($it as $file) {
        if (!$file->isFile() || strtolower($file->getExtension()) !== 'php') continue;
        $path = str_replace('\\', '/', $file->getPathname());
        if (str_contains($path, '/node_modules/') || str_contains($path, '/vendor/')) continue;
        if (str_starts_with($path, "$root/Modules/Shared/")) continue;
        if (str_ends_with($path, 'tests/Architecture/SharedMigrationGuardTest.php')) continue;
        $rel = ltrim(substr($path, strlen($root)), '/');
        if (in_array($rel, $oldFiles, true)) continue;

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

echo ($dry ? '[DRY-RUN] ' : '') . "استبدال $total استيراد بـ " . count($changed) . " ملف:\n";
foreach ($changed as $f => $c) echo "  - $f ($c)\n";

foreach ($oldFiles as $f) {
    if (is_file("$root/$f")) {
        echo ($dry ? '[DRY-RUN] ' : '') . "حذف $f\n";
        if (!$dry) unlink("$root/$f");
    }
}
if (!$dry) {
    foreach (['app/Models', 'app/Services'] as $d) {
        if (is_dir("$root/$d") && count(scandir("$root/$d")) === 2) rmdir("$root/$d");
    }
}

echo "\nالخطوة الجاية: composer dump-autoload ; php artisan optimize:clear ; vendor\\bin\\pest tests\\Architecture ; php artisan test\n";

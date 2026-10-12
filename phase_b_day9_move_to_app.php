<?php

/**
 * المرحلة ب - يوم 9: نقل Examination / Finance / School / SMS لبنية nwidart الموحّدة (الكود جوا app/).
 *
 * الاستخدام (من جذر المشروع):
 *   php phase_b_day9_move_to_app.php --dry-run
 *   php phase_b_day9_move_to_app.php
 *
 * الـ namespaces ما بتتغير (Modules\Examination\... نفسها)، بس مكان الملفات الفعلي بيتغير.
 * بيبقى بجذر الموديول: config, database, resources, routes, tests, lang, module.json, composer.json ...
 */

$root = __DIR__;
$dry = in_array('--dry-run', $argv, true);
$modules = ['Examination', 'Finance', 'School', 'SMS'];
$keep = ['app', 'config', 'database', 'resources', 'routes', 'tests', 'lang', 'public', 'node_modules', 'vendor'];

function moveInto(string $src, string $dst, bool $dry, array &$log): void
{
    if (is_file($src)) {
        if (file_exists($dst)) {
            fwrite(STDERR, "CONFLICT: $dst موجود مسبقاً، وقفت.\n");
            exit(1);
        }
        $log[] = "$src -> $dst";
        if (!$dry) {
            if (!is_dir(dirname($dst))) mkdir(dirname($dst), 0777, true);
            rename($src, $dst);
        }
        return;
    }
    foreach (scandir($src) as $item) {
        if ($item === '.' || $item === '..') continue;
        moveInto("$src/$item", "$dst/$item", $dry, $log);
    }
    if (!$dry) @rmdir($src);
}

foreach ($modules as $m) {
    $base = "$root/Modules/$m";
    if (!is_dir($base)) { fwrite(STDERR, "ERROR: $base غير موجود\n"); exit(1); }

    $dirs = [];
    foreach (scandir($base) as $item) {
        if ($item === '.' || $item === '..') continue;
        if (is_dir("$base/$item") && !in_array(strtolower($item), $keep, true)) $dirs[] = $item;
    }

    $log = [];
    foreach ($dirs as $d) {
        moveInto("$base/$d", "$base/app/$d", $dry, $log);
    }
    echo ($dry ? '[DRY-RUN] ' : '') . "$m: نقل " . count($log) . " ملف من (" . implode(', ', $dirs) . ")\n";
}

// Examination: أسطر المسارات القديمة بالـ Provider (بتعتمد على __DIR__ وبتنكسر بعد النقل، ومكررة أصلاً مع RouteServiceProvider)
$provider = "$root/Modules/Examination/" . ($dry ? '' : 'app/') . 'Providers/ExaminationServiceProvider.php';
if (is_file($provider)) {
    $lines = file($provider);
    $out = [];
    $removed = 0;
    foreach ($lines as $line) {
        if (preg_match("#loadRoutesFrom\(__DIR__ \. '/\.\./routes/(api|web)\.php'\);#", $line)
            || str_contains($line, "'/../Database/Migrations'")) {
            $removed++;
            continue;
        }
        $out[] = $line;
    }
    echo ($dry ? '[DRY-RUN] ' : '') . "ExaminationServiceProvider: حذف $removed سطر (مسارات __DIR__ المكررة)\n";
    if (!$dry && $removed) file_put_contents($provider, implode('', $out));
}

echo "\nالخطوة الجاية: composer dump-autoload ; php artisan optimize:clear ; php artisan route:list ; vendor\\bin\\pest tests\\Architecture ; php artisan test\n";

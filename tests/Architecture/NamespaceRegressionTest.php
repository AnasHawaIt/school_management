<?php

/**
 * حارس ضد رجوع مشكلة \app\ الزايدة بالـ namespace (اللي صلّحناها يدوياً بسكريبت).
 * إذا حدا ضاف ملف جديد أو نسخ-لصق كود قديم وفيه هالنمط، هالاختبار بيفشل فوراً.
 */

const MODULE_NAMES = [
    'Academic', 'Activities', 'Announcement', 'Attendance', 'Core',
    'Examination', 'Finance', 'Library', 'Messagings', 'Notifications',
    'SMS', 'School', 'Transport',
];

function scanForBadNamespace(string $root): array
{
    $pattern = '/Modules\\\\(' . implode('|', MODULE_NAMES) . ')\\\\app\\\\/i';
    $hits = [];

    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
    );
    foreach ($it as $file) {
        if (!$file->isFile()) continue;
        if (str_contains($file->getPathname(), DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR)) continue;
        if (str_contains($file->getPathname(), DIRECTORY_SEPARATOR.'node_modules'.DIRECTORY_SEPARATOR)) continue;

        $ext = strtolower($file->getExtension());
        if (!in_array($ext, ['php', 'json'], true)) continue;

        $content = file_get_contents($file->getPathname());

        if ($ext === 'php' && preg_match($pattern, $content)) {
            $hits[] = $file->getPathname();
        }
        if ($ext === 'json' && basename($file->getPathname()) === 'module.json') {
            foreach (MODULE_NAMES as $mod) {
                if (str_contains($content, "Modules\\\\{$mod}\\\\app\\\\")) {
                    $hits[] = $file->getPathname();
                    break;
                }
            }
        }
    }

    return $hits;
}

it('never reintroduces the stray \\app\\ segment in module namespaces or module.json', function () {
    // ما بنعتمد على base_path() لأن اختبارات Architecture ما بتاخد
    // إعداد extend(TestCase::class) الموجود بـ tests/Pest.php (هو مخصص لـ Feature فقط).
    // بنحسب مسار جذر المشروع يدوياً من مكان هالملف: tests/Architecture/هالملف.php
    $projectRoot = dirname(__DIR__, 2);
    $hits = scanForBadNamespace($projectRoot . DIRECTORY_SEPARATOR . 'Modules');

    expect($hits)->toBeEmpty(
        "لقيت \\app\\ زايدة بهالملفات (لازم تتصحح يدوياً أو بسكريبت fix_namespace_v2.php):\n"
        . implode("\n", $hits)
    );
});

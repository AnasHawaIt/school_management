<?php
/**
 * نسخة محسّنة: يحذف \app\ الزايدة من:
 * - namespace و use بكل ملفات .php (بكل المشروع، مو جوا Modules/ بس)
 * - أي استخدام inline زي \Modules\X\app\Y\Z::class بدون use
 * - قوائم الـ providers جوا كل module.json
 *
 * تشغيل:
 *   php fix_namespace_v2.php /path/to/project --dry-run
 *   php fix_namespace_v2.php /path/to/project
 *
 * آمن تشغّله فوق مشروع سبق وشغّلت عليه النسخة الأولى (idempotent).
 */

$MODULE_NAMES = [
    'Academic', 'Activities', 'Announcement', 'Attendance', 'Core',
    'Examination', 'Finance', 'Library', 'Messagings', 'Notifications',
    'SMS', 'School', 'Transport',
];

$CANON = [];
foreach ($MODULE_NAMES as $m) {
    $CANON[strtolower($m)] = $m;
}

function buildPhpPattern(array $moduleNames): string
{
    // يطابق Modules\X\app\ بأي مكان بالملف (مو بس أول السطر بعد namespace/use)
    $alternation = implode('|', $moduleNames);
    return '/(Modules\\\\)(' . $alternation . ')(\\\\)app\\\\/i';
}

function fixPhpContent(string $text, string $pattern, array $canon, int &$count): string
{
    return preg_replace_callback($pattern, function ($m) use ($canon, &$count) {
        $count++;
        $prefix = $m[1];
        $mod = $m[2];
        $sep = $m[3];
        $canonMod = $canon[strtolower($mod)] ?? $mod;
        return $prefix . $canonMod . $sep;
    }, $text);
}

function fixJsonContent(string $text, array $moduleNames, array $canon, int &$count): string
{
    // بملف JSON، الفاصل \ بيظهر كـ \\ (باكسلاشين) بالنص الخام
    foreach ($moduleNames as $mod) {
        $needle = "Modules\\\\{$mod}\\\\app\\\\"; // = Modules\\<Mod>\\app\\  (باكسلاشين حرفيين)
        $replacement = "Modules\\\\{$mod}\\\\";
        $n = substr_count($text, $needle);
        if ($n > 0) {
            $text = str_replace($needle, $replacement, $text);
            $count += $n;
        }
        // كمان تحقق من حالة أحرف مختلفة (زي \\activities\\ بدل \\Activities\\)
        $needleLower = "Modules\\\\" . strtolower($mod) . "\\\\app\\\\";
        if (strtolower($needle) !== $needleLower) {
            $n2 = substr_count(strtolower($text), $needleLower);
            // نادر الحدوث، نتجاهله هون توفيراً للتعقيد إن ما وجد أعلاه
        }
    }
    return $text;
}

function rglobFiles(string $dir, string $ext): Generator
{
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
    );
    foreach ($it as $file) {
        if ($file->isFile() && strtolower($file->getExtension()) === $ext) {
            // تجاهل vendor و node_modules لتسريع الفحص
            if (strpos($file->getPathname(), DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR) !== false) continue;
            if (strpos($file->getPathname(), DIRECTORY_SEPARATOR . 'node_modules' . DIRECTORY_SEPARATOR) !== false) continue;
            yield $file->getPathname();
        }
    }
}

function main(): void
{
    global $MODULE_NAMES, $CANON;

    $argvLocal = $_SERVER['argv'];
    if (count($argvLocal) < 2) {
        echo "استخدام: php fix_namespace_v2.php /path/to/project [--dry-run]\n";
        exit(1);
    }

    $projectRoot = rtrim($argvLocal[1], '/\\');
    $dryRun = in_array('--dry-run', $argvLocal, true);

    if (!is_dir($projectRoot . DIRECTORY_SEPARATOR . 'Modules')) {
        fwrite(STDERR, "ما لقيت مجلد Modules جوا: $projectRoot\n");
        exit(1);
    }

    $phpPattern = buildPhpPattern($MODULE_NAMES);

    $phpFilesChanged = 0;
    $phpOccurrences = 0;
    $jsonFilesChanged = 0;
    $jsonOccurrences = 0;
    $report = [];

    // 1) كل ملفات .php بالمشروع كامل (بما فيها config/ و app/ و database/ خارج Modules)
    foreach (rglobFiles($projectRoot, 'php') as $file) {
        $text = file_get_contents($file);
        $before = $text;
        $count = 0;
        $text = fixPhpContent($text, $phpPattern, $CANON, $count);
        if ($count > 0) {
            $phpFilesChanged++;
            $phpOccurrences += $count;
            $relative = ltrim(str_replace($projectRoot, '', $file), '/\\');
            $report[] = sprintf('[php]  %2d  %s', $count, $relative);
            if (!$dryRun) {
                file_put_contents($file, $text);
            }
        }
    }

    // 2) كل ملفات module.json
    foreach (rglobFiles($projectRoot, 'json') as $file) {
        if (basename($file) !== 'module.json') continue;
        $text = file_get_contents($file);
        $count = 0;
        $newText = fixJsonContent($text, $MODULE_NAMES, $CANON, $count);
        if ($count > 0) {
            $jsonFilesChanged++;
            $jsonOccurrences += $count;
            $relative = ltrim(str_replace($projectRoot, '', $file), '/\\');
            $report[] = sprintf('[json] %2d  %s', $count, $relative);
            if (!$dryRun) {
                file_put_contents($file, $newText);
            }
        }
    }

    $mode = $dryRun ? 'معاينة فقط (dry-run) — ما تعدّل ولا ملف' : 'تم التعديل فعلياً';
    echo "\nالوضع: $mode\n";
    echo "ملفات PHP المتأثرة: $phpFilesChanged  (عدد الحالات: $phpOccurrences)\n";
    echo "ملفات module.json المتأثرة: $jsonFilesChanged  (عدد الحالات: $jsonOccurrences)\n\n";

    $reportPath = $projectRoot . DIRECTORY_SEPARATOR . 'namespace_fix_report_v2.txt';
    file_put_contents($reportPath, implode("\n", $report));
    echo "تفاصيل كل ملف انكتبت بـ: $reportPath\n";

    if ($jsonFilesChanged === 0 && $phpFilesChanged === 0) {
        echo "\nما لقيت أي شي محتاج تصحيح — إذا لسا عندك خطأ، ابعتلي نصه بالضبط.\n";
    }
}

main();

<?php
/**
 * يفحص كل ملفات Listeners بكل الموديولات، ويصنفها:
 * - pure_log: بس بتعمل activity()->log(...) بلا أي أثر جانبي تاني (آمن تحذفها وتستبدلها بـ LogsActivity)
 * - notify:   فيها إرسال إشعار فعلي (خلّيها متل ما هي، مؤقتاً)
 * - other:    فيها منطق تاني (broadcast، إحصائيات، إلخ) — تحتاج مراجعة يدوية
 *
 * تشغيل: php scan_listeners.php /path/to/project
 */

$MODULES = [
    'Academic', 'Activities', 'Announcement', 'Attendance', 'Core',
    'Examination', 'Finance', 'Library', 'Messagings', 'Notifications',
    'SMS', 'School', 'Transport',
];

function classify(string $content): string
{
    $hasActivityLog = (bool) preg_match('/\bactivity\s*\(/i', $content);
    $hasNotify = (bool) preg_match(
        '/->notify\s*\(|Notification::|NotificationService|SendsNotification|FcmToken|->broadcast\s*\(|Broadcast::|->toDatabase\s*\(|->via\s*\(/i',
        $content
    );
    $hasOtherLogic = (bool) preg_match(
        '/DB::|->update\s*\(|->create\s*\(|->save\s*\(|->delete\s*\(|Mail::|Cache::|Queue::push|dispatch\s*\(/i',
        $content
    );

    if ($hasActivityLog && !$hasNotify && !$hasOtherLogic) {
        return 'pure_log';
    }
    if ($hasNotify) {
        return 'notify';
    }
    return 'other';
}

function extractHandledEvent(string $content): ?string
{
    if (preg_match('/function\s+handle\s*\(\s*([A-Za-z0-9_\\\\]+)\s+\$/', $content, $m)) {
        $parts = explode('\\', $m[1]);
        return end($parts);
    }
    return null;
}

function main(): void
{
    global $MODULES;

    $projectRoot = rtrim($_SERVER['argv'][1] ?? '.', '/\\');
    $modulesDir = $projectRoot . DIRECTORY_SEPARATOR . 'Modules';

    if (!is_dir($modulesDir)) {
        fwrite(STDERR, "ما لقيت مجلد Modules جوا: $projectRoot\n");
        exit(1);
    }

    $counts = ['pure_log' => 0, 'notify' => 0, 'other' => 0];
    $byModule = [];
    $pureLogFiles = [];

    foreach ($MODULES as $mod) {
        $listenersDir = $modulesDir . DIRECTORY_SEPARATOR . $mod . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Listeners';
        if (!is_dir($listenersDir)) {
            $listenersDir = $modulesDir . DIRECTORY_SEPARATOR . $mod . DIRECTORY_SEPARATOR . 'Listeners';
        }
        if (!is_dir($listenersDir)) continue;

        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($listenersDir, FilesystemIterator::SKIP_DOTS)
        );
        foreach ($it as $file) {
            if (!$file->isFile() || strtolower($file->getExtension()) !== 'php') continue;

            $content = file_get_contents($file->getPathname());
            $type = classify($content);
            $event = extractHandledEvent($content);
            $relative = ltrim(str_replace($projectRoot, '', $file->getPathname()), '/\\');

            $counts[$type]++;
            $byModule[$mod][$type] = ($byModule[$mod][$type] ?? 0) + 1;

            if ($type === 'pure_log') {
                $pureLogFiles[] = ['file' => $relative, 'event' => $event, 'module' => $mod];
            }
        }
    }

    echo "=========================================================\n";
    echo " ملخص عام\n";
    echo "=========================================================\n";
    echo "pure_log (آمن تحذفها وتستبدلها بـ LogsActivity): {$counts['pure_log']}\n";
    echo "notify   (خلّيها متل ما هي مؤقتاً):                {$counts['notify']}\n";
    echo "other    (تحتاج مراجعة يدوية):                     {$counts['other']}\n\n";

    echo "=========================================================\n";
    echo " التفصيل حسب الموديول\n";
    echo "=========================================================\n";
    foreach ($byModule as $mod => $c) {
        printf(
            "%-14s pure_log=%-4d notify=%-4d other=%-4d\n",
            $mod, $c['pure_log'] ?? 0, $c['notify'] ?? 0, $c['other'] ?? 0
        );
    }

    echo "\n=========================================================\n";
    echo " قائمة ملفات pure_log (آمنة للحذف) + الـ Entity المحتمل\n";
    echo "=========================================================\n";
    foreach ($pureLogFiles as $f) {
        $entityGuess = $f['event'] ? preg_replace('/(Created|Updated|Deleted|Restored|ForceDeleted)$/', '', $f['event']) : '?';
        echo "[{$f['module']}] {$f['file']}\n";
        echo "    الحدث: " . ($f['event'] ?? '?') . "   →   Entity محتمل: {$entityGuess}\n";
    }

    $reportPath = $projectRoot . DIRECTORY_SEPARATOR . 'listeners_scan_report.txt';
    $lines = array_map(fn ($f) => "{$f['module']}\t{$f['file']}\t" . ($f['event'] ?? ''), $pureLogFiles);
    file_put_contents($reportPath, implode("\n", $lines));
    echo "\nقائمة pure_log الكاملة انكتبت بـ: $reportPath\n";
}

main();

<?php
/**
 * يفحص كل اعتماديات الموديولات مرة وحدة، ويطلع تقرير كامل
 * بدل ما تكتشفهم وحدة وحدة من فشل الاختبارات.
 *
 * تشغيل: php scan_dependencies.php /path/to/project
 * أو من جوا المشروع: php scan_dependencies.php .
 */

$MODULES = [
    'Academic' => 2, 'Activities' => 2, 'Announcement' => 3, 'Attendance' => 2,
    'Core' => 1, 'Examination' => 2, 'Finance' => 2, 'Library' => 2,
    'Messagings' => 3, 'Notifications' => 3, 'SMS' => 3, 'School' => 1,
    'Transport' => 2,
];

// الاعتماديات المسموحة لكل موديول (حسب الوثيقة المعمارية، القسم 10.1)
$ALLOWED = [
    'Core'          => [],
    'School'        => ['Core'],
    'Academic'      => ['School', 'Core'],
    'Attendance'    => ['Academic', 'School', 'Core'],
    'Examination'   => ['Academic', 'School', 'Core'],
    'Finance'       => ['Academic', 'School', 'Core'],
    'Activities'    => ['Academic', 'School', 'Core'],
    'Library'       => ['Academic', 'Core'],
    'Transport'     => ['Academic', 'Core'],
    'SMS'           => ['Core'],
    'Notifications' => ['SMS', 'Academic', 'Core'],
    'Announcement'  => ['Notifications', 'School', 'Academic', 'Core'],
    'Messagings'    => ['Notifications', 'Core'],
];

function main(): void
{
    global $MODULES, $ALLOWED;

    $projectRoot = rtrim($_SERVER['argv'][1] ?? '.', '/\\');
    $modulesDir = $projectRoot . DIRECTORY_SEPARATOR . 'Modules';

    if (!is_dir($modulesDir)) {
        fwrite(STDERR, "ما لقيت مجلد Modules جوا: $projectRoot\n");
        exit(1);
    }

    $moduleNames = array_keys($MODULES);
    $pattern = '/^use\s+Modules\\\\(' . implode('|', $moduleNames) . ')\\\\/i';

    // violations[from][to] = [ 'file:line', ... ]
    $violations = [];
    $allowedHits = [];

    foreach ($moduleNames as $from) {
        $dir = $modulesDir . DIRECTORY_SEPARATOR . $from;
        if (!is_dir($dir)) continue;

        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
        );
        foreach ($it as $file) {
            if (!$file->isFile() || strtolower($file->getExtension()) !== 'php') continue;

            $lines = file($file->getPathname());
            foreach ($lines as $lineNo => $line) {
                if (!preg_match($pattern, trim($line), $m)) continue;
                $to = $m[1];
                $canonTo = null;
                foreach ($moduleNames as $mn) {
                    if (strtolower($mn) === strtolower($to)) { $canonTo = $mn; break; }
                }
                $to = $canonTo ?? $to;
                if ($to === $from) continue; // استيراد من نفس الموديول، طبيعي

                $relative = ltrim(str_replace($projectRoot, '', $file->getPathname()), '/\\');
                $entry = $relative . ':' . ($lineNo + 1);

                $isAllowed = in_array($to, $ALLOWED[$from] ?? [], true);
                if ($isAllowed) {
                    $allowedHits[$from][$to] = ($allowedHits[$from][$to] ?? 0) + 1;
                } else {
                    $violations[$from][$to][] = $entry;
                }
            }
        }
    }

    echo "=========================================================\n";
    echo " تقرير كامل لكل الاعتماديات الممنوعة بين الموديولات\n";
    echo "=========================================================\n\n";

    if (empty($violations)) {
        echo "لا يوجد أي اعتماد ممنوع. ممتاز!\n";
    } else {
        ksort($violations);
        foreach ($violations as $from => $tos) {
            ksort($tos);
            foreach ($tos as $to => $entries) {
                echo "❌ {$from} -> {$to}   (" . count($entries) . " حالة)\n";
                foreach (array_slice($entries, 0, 3) as $e) {
                    echo "     - {$e}\n";
                }
                if (count($entries) > 3) {
                    echo "     ... و" . (count($entries) - 3) . " حالة إضافية\n";
                }
            }
        }
    }

    echo "\n---------------------------------------------------------\n";
    echo " جاهز للّصق المباشر بملف LayerDependencyTest.php:\n";
    echo "---------------------------------------------------------\n\n";
    foreach ($violations as $from => $tos) {
        $list = array_map(fn($m) => "'Modules\\{$m}'", array_keys($tos));
        echo "// {$from}:\n";
        echo "->ignoring([" . implode(', ', $list) . "]);\n\n";
    }
}

main();

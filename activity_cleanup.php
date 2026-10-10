<?php
/**
 * أداة تنظيف الـ Listeners (مع شبكة أمان):
 *   - نسخة احتياطية لكل ملف قبل تعديله/حذفه (.cleanup_backup/)
 *   - php -l لكل ملف PHP معدّل، ولو فشل يرجّع النسخة القديمة تلقائياً
 *   - ما بتحذف listener إذا لقيت إله أي مرجع تاني بالمشروع
 *
 * الأوامر:
 *   php activity_cleanup.php fix-options <project> [--dry-run]
 *       يصلّح getActivitylogOptions() بالـ Entities يلي عدّلناها قبل بدون تحديد حقول
 *       (كانت بتسجّل الحدث بدون القيم القديمة/الجديدة)
 *
 *   php activity_cleanup.php academic <project> [--dry-run]
 *       يضيف LogsActivity لـ 13 Entity، يحذف 50 Log listener، يعدّل EventServiceProvider،
 *       ويضيف الـ morph map الناقص. بيخلّي 12 listener (pivot / bulk insert) متل ما هم.
 */

// [module, class, logName]  (logName = morph alias كمان)
const ENTITY_TARGETS = [
    ['Academic', 'Counselor', 'counselor'],
    ['Academic', 'Guardian', 'guardian'],
    ['Academic', 'InspectionProgram', 'inspection_program'],
    ['Academic', 'Student', 'student'],
    ['Academic', 'StudentMedicalRecord', 'student_medical_record'],
    ['Academic', 'StudentPoint', 'student_point'],
    ['Academic', 'Subject', 'subject'],
    ['Academic', 'Teacher', 'teacher'],
    ['Academic', 'TeacherQualification', 'teacher_qualification'],
    ['Academic', 'Timetable', 'timetable'],
    ['Attendance', 'LeaveRequest', 'leave_request'],
    ['Attendance', 'StudentAttendance', 'student_attendance'],
    ['Attendance', 'TeacherAttendance', 'teacher_attendance'],
];

const LISTENERS_TO_REMOVE = [
    // Counselor
    'LogCounselorCreated', 'LogCounselorDeleted', 'LogCounselorRestored', 'LogCounselorUpdated', 'LogCounselorStatusToggled',
    // Guardian
    'LogGuardianCreated', 'LogGuardianDeleted', 'LogGuardianRestored', 'LogGuardianUpdated',
    // InspectionProgram
    'LogInspectionProgramCreated', 'LogInspectionProgramDeleted', 'LogInspectionProgramRestored',
    'LogInspectionProgramUpdated', 'LogInspectionProgramSetCurrent', 'LogInspectionProgramStatusUpdated',
    // Subject
    'LogSubjectCreated', 'LogSubjectDeleted', 'LogSubjectRestored', 'LogSubjectUpdated',
    // Student
    'LogStudentCreated', 'LogStudentDeleted', 'LogStudentRestored', 'LogStudentUpdated',
    'LogStudentStatusUpdated', 'LogStudentPromoted', 'LogStudentTransferred',
    // StudentMedicalRecord
    'LogMedicalRecordUpdated',
    // Teacher
    'LogTeacherCreated', 'LogTeacherDeleted', 'LogTeacherRestored', 'LogTeacherUpdated', 'LogTeacherStatusToggled',
    // TeacherQualification
    'LogTeacherQualificationAdded', 'LogTeacherQualificationDeleted',
    // Timetable
    'LogTimetableEntryCreated', 'LogTimetableEntryDeleted', 'LogTimetableEntryUpdated',
    // StudentPoint (الـ Bulk بيضل)
    'LogStudentPointGiven', 'LogStudentPointDeleted',
    // LeaveRequest (entity بموديول Attendance)
    'LogLeaveRequestApproved', 'LogLeaveRequestCreated', 'LogLeaveRequestDeleted',
    'LogLeaveRequestRejected', 'LogLeaveRequestUpdated',
    // StudentAttendance (الـ Bulk بيضل)
    'LogStudentAttendanceDeleted', 'LogStudentAttendanceRecorded', 'LogStudentAttendanceUpdated',
    // TeacherAttendance
    'LogTeacherAttendanceDeleted', 'LogTeacherAttendanceRecorded', 'LogTeacherAttendanceUpdated',
];

const LISTENERS_TO_KEEP = [
    'LogCounselorSectionAssigned', 'LogCounselorSectionUnassigned',
    'LogStudentAttachedToGuardian', 'LogStudentDetachedFromGuardian',
    'LogTeacherAssignedToSubject', 'LogTeacherUnassignedFromSubject',
    'LogCounselorAssignedToInspectionProgram', 'LogCounselorUnassignedFromInspectionProgram',
    'LogObservationSubmitted', 'LogStudentAssignedToSection',
    'LogStudentPointsBulkGiven', 'LogStudentAttendanceBulkRecorded',
];

// ---------------------------------------------------------------- helpers

function out(string $s): void { echo $s . "\n"; }

function lintFile(string $file): bool
{
    $cmd = escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($file) . ' 2>&1';
    exec($cmd, $o, $code);
    return $code === 0;
}

function relPath(string $root, string $file): string
{
    return ltrim(str_replace('\\', '/', substr($file, strlen($root))), '/');
}

final class Backup
{
    private string $dir;
    private array $saved = [];

    public function __construct(private string $root)
    {
        $this->dir = $root . '/.cleanup_backup/' . date('Ymd_His');
    }

    public function dir(): string { return $this->dir; }

    public function save(string $file): void
    {
        if (isset($this->saved[$file]) || !is_file($file)) return;
        $dest = $this->dir . '/' . relPath($this->root, $file);
        @mkdir(dirname($dest), 0777, true);
        copy($file, $dest);
        $this->saved[$file] = $dest;
    }

    public function restore(string $file): void
    {
        if (isset($this->saved[$file])) copy($this->saved[$file], $file);
    }
}

/** @return Generator<string> */
function phpFiles(string $root, array $subdirs): Generator
{
    foreach ($subdirs as $sub) {
        $dir = $root . '/' . $sub;
        if (!is_dir($dir)) continue;
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
        foreach ($it as $f) {
            if (!$f->isFile() || strtolower($f->getExtension()) !== 'php') continue;
            $p = str_replace('\\', '/', $f->getPathname());
            if (str_contains($p, '/vendor/') || str_contains($p, '/node_modules/') || str_contains($p, '/.cleanup_backup/')) continue;
            yield $p;
        }
    }
}

function writeChecked(string $file, string $newContent, Backup $bk, bool $dry): bool
{
    if ($dry) return true;
    $bk->save($file);
    file_put_contents($file, $newContent);
    if (!lintFile($file)) {
        $bk->restore($file);
        return false;
    }
    return true;
}

// ---------------------------------------------------------------- options builder

function selectorFor(string $c): string
{
    if (preg_match('/\$fillable\s*=\s*\[\s*[\'"]/', $c)) return '->logFillable()';
    if (preg_match('/\$guarded\s*=/', $c)) return '->logUnguarded()';
    return "->logAll()\n            ->dontLogIfAttributesChangedOnly(['updated_at'])";
}

function buildMethod(string $c, string $logName, string $nl): string
{
    $sel = selectorFor($c);
    $except = preg_match('/[\'"]password[\'"]/', $c)
        ? "\n            ->logExcept(['password', 'remember_token'])" : '';
    $m = "    public function getActivitylogOptions(): LogOptions\n"
        . "    {\n"
        . "        return LogOptions::defaults()\n"
        . "            {$sel}\n"
        . "            ->logOnlyDirty(){$except}\n"
        . "            ->useLogName('{$logName}');\n"
        . "    }\n";
    return str_replace("\n", $nl, $m);
}

// ---------------------------------------------------------------- entity patch

function patchEntity(string $c, string $logName): array
{
    if (str_contains($c, 'LogsActivity') || str_contains($c, 'getActivitylogOptions')) {
        return ['skip', 'فيها LogsActivity من قبل'];
    }
    $nl = str_contains($c, "\r\n") ? "\r\n" : "\n";

    if (!preg_match('/^(?:abstract\s+|final\s+)?class\s+\w+[^{]*\{[ \t]*\r?\n/m', $c, $m, PREG_OFFSET_CAPTURE)) {
        return ['fail', 'ما لقيت تعريف الكلاس'];
    }
    $classPos = $m[0][1];
    $afterBrace = $m[0][1] + strlen($m[0][0]);

    $lastBrace = strrpos($c, '}');
    if ($lastBrace === false || $lastBrace < $afterBrace) {
        return ['fail', 'ما لقيت نهاية الكلاس'];
    }

    $header = substr($c, 0, $classPos);
    if (preg_match_all('/^use\s+[^;]+;[ \t]*\r?\n/m', $header, $mm, PREG_OFFSET_CAPTURE) && count($mm[0])) {
        $last = end($mm[0]);
        $importAt = $last[1] + strlen($last[0]);
        $prefix = '';
    } elseif (preg_match('/^namespace\s+[^;]+;[ \t]*\r?\n/m', $header, $nm, PREG_OFFSET_CAPTURE)) {
        $importAt = $nm[0][1] + strlen($nm[0][0]);
        $prefix = $nl;
    } else {
        return ['fail', 'ما لقيت مكان للـ imports'];
    }

    // من الأسفل للأعلى عشان الـ offsets تضل صحيحة
    $before = rtrim(substr($c, 0, $lastBrace)) . $nl . $nl;
    $c2 = $before . buildMethod($c, $logName, $nl) . substr($c, $lastBrace);
    $c2 = substr($c2, 0, $afterBrace) . '    use LogsActivity;' . $nl . substr($c2, $afterBrace);
    $imports = $prefix . 'use Spatie\\Activitylog\\LogOptions;' . $nl . 'use Spatie\\Activitylog\\Traits\\LogsActivity;' . $nl;
    $c2 = substr($c2, 0, $importAt) . $imports . substr($c2, $importAt);

    return ['ok', $c2];
}

function entityPath(string $root, string $mod, string $cls): ?string
{
    foreach (["Modules/$mod/app/Entities/$cls.php", "Modules/$mod/Entities/$cls.php"] as $rel) {
        if (is_file("$root/$rel")) return "$root/$rel";
    }
    return null;
}

// ---------------------------------------------------------------- provider cleanup

function findProvider(string $root, string $mod): ?string
{
    foreach (["Modules/$mod/app/Providers/EventServiceProvider.php", "Modules/$mod/Providers/EventServiceProvider.php"] as $rel) {
        if (is_file("$root/$rel")) return "$root/$rel";
    }
    return null;
}

function cleanProviderContent(string $c, array $classes): string
{
    foreach ($classes as $cls) {
        $q = preg_quote($cls, '/');
        $c = preg_replace('/^use\s+[^;]*\\\\' . $q . ';[ \t]*\r?\n/m', '', $c);
        $c = preg_replace('/^[ \t]*' . $q . '::class,?[ \t]*(\/\/[^\r\n]*)?\r?\n/m', '', $c);
    }
    return $c;
}

// ---------------------------------------------------------------- morph map

function registeredAliases(string $root): array
{
    $found = [];
    foreach (phpFiles($root, ['app/Providers', 'Modules']) as $f) {
        if (!str_ends_with($f, 'ServiceProvider.php')) continue;
        $c = file_get_contents($f);
        if (!str_contains($c, 'orphMap')) continue;
        if (preg_match_all('/[\'"]([a-z0-9_]+)[\'"]\s*=>\s*[\\\\A-Za-z0-9_]+::class/', $c, $m)) {
            foreach ($m[1] as $a) $found[$a] = true;
        }
    }
    return $found;
}

function addMorphEntries(string $c, array $entries): ?string
{
    if (!preg_match('/(Relation::enforceMorphMap\(\[)(.*?)(\r?\n[ \t]*\]\);)/s', $c, $m, PREG_OFFSET_CAPTURE)) return null;
    $nl = str_contains($c, "\r\n") ? "\r\n" : "\n";
    $body = rtrim($m[2][0]);
    $lines = preg_split('/\r?\n/', $body);
    $lastIdx = count($lines) - 1;
    if (!preg_match('/,\s*(\/\/.*)?$/', $lines[$lastIdx])) {
        $lines[$lastIdx] = preg_replace('/^(.*?\S)(\s*\/\/.*)?$/', '$1,$2', $lines[$lastIdx]);
    }
    $body = implode($nl, $lines);
    foreach ($entries as $alias => $fqcn) {
        $body .= $nl . "            '{$alias}' => \\{$fqcn}::class,";
    }
    return substr($c, 0, $m[2][1]) . $body . substr($c, $m[3][1]);
}

// ---------------------------------------------------------------- commands

function cmdFixOptions(string $root, bool $dry): void
{
    $bk = new Backup($root);
    $fixed = $skipped = $failed = 0;
    foreach (phpFiles($root, ['Modules']) as $f) {
        $c = file_get_contents($f);
        if (!str_contains($c, 'getActivitylogOptions')) continue;
        if (preg_match('/logFillable\(|logUnguarded\(|logOnly\(|logAll\(/', $c)) { $skipped++; continue; }

        $sel = selectorFor($c);
        $add = $sel;
        if (preg_match('/[\'"]password[\'"]/', $c) && !str_contains($c, 'logExcept(')) {
            $add .= "->logExcept(['password', 'remember_token'])";
        }
        $new = preg_replace('/LogOptions::defaults\(\)/', 'LogOptions::defaults()' . $add, $c, 1, $n);
        if ($n !== 1) { out("  [فشل]   " . relPath($root, $f) . "  (ما لقيت LogOptions::defaults())"); $failed++; continue; }

        if (writeChecked($f, $new, $bk, $dry)) { out("  [" . ($dry ? 'سيُصلَّح' : 'تم') . "]   " . relPath($root, $f)); $fixed++; }
        else { out("  [تراجع] " . relPath($root, $f) . "  (فشل php -l، رجعت النسخة القديمة)"); $failed++; }
    }
    out("\nانصلح: $fixed | كان سليم: $skipped | فشل: $failed");
    if (!$dry && $fixed) out("نسخ احتياطية بـ: " . $bk->dir());
}

function cmdAcademic(string $root, bool $dry): void
{
    $bk = new Backup($root);
    $problems = 0;

    // ---- 1) Entities
    out("== 1) إضافة LogsActivity للـ Entities ==");
    $aliasMap = [];
    foreach (ENTITY_TARGETS as [$mod, $cls, $logName]) {
        $path = entityPath($root, $mod, $cls);
        if (!$path) { out("  [فشل]   $mod/$cls  (الملف مش موجود)"); $problems++; continue; }
        [$st, $res] = patchEntity(file_get_contents($path), $logName);
        if ($st === 'skip') { out("  [تخطي]  $cls  ($res)"); }
        elseif ($st === 'fail') { out("  [فشل]   $cls  ($res)"); $problems++; continue; }
        else {
            if (writeChecked($path, $res, $bk, $dry)) out("  [" . ($dry ? 'سيُعدَّل' : 'تم') . "]   $cls");
            else { out("  [تراجع] $cls  (فشل php -l، رجعت النسخة القديمة)"); $problems++; continue; }
        }
        $aliasMap[$logName] = "Modules\\{$mod}\\Entities\\{$cls}";
    }

    // ---- 2) Morph map
    out("\n== 2) morph map ==");
    $registered = registeredAliases($root);
    $new = [];
    foreach ($aliasMap as $alias => $fq) if (!isset($registered[$alias])) $new[$alias] = $fq;
    $appProv = "$root/app/Providers/AppServiceProvider.php";
    if (!$new) out("  كل الـ aliases مسجّلة من قبل");
    elseif (!is_file($appProv)) { out("  [فشل] AppServiceProvider مش موجود"); $problems++; }
    else {
        $res = addMorphEntries(file_get_contents($appProv), $new);
        if ($res === null) { out("  [فشل] ما لقيت Relation::enforceMorphMap([...]) بـ AppServiceProvider"); $problems++; }
        elseif (writeChecked($appProv, $res, $bk, $dry)) out("  [" . ($dry ? 'سيُضاف' : 'تم') . "] " . implode(', ', array_keys($new)));
        else { out("  [تراجع] AppServiceProvider (فشل php -l)"); $problems++; }
    }

    // ---- 3) Listeners
    out("\n== 3) الـ Listeners ==");
    $provider = findProvider($root, 'Academic');
    if (!$provider) { out("  [فشل] EventServiceProvider تبع Academic مش موجود"); exit(1); }

    $targets = [];   // class => path
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("$root/Modules/Academic", FilesystemIterator::SKIP_DOTS));
    $byName = [];
    foreach ($it as $f) {
        if ($f->isFile() && strtolower($f->getExtension()) === 'php') {
            $byName[$f->getBasename('.php')][] = str_replace('\\', '/', $f->getPathname());
        }
    }
    foreach (LISTENERS_TO_REMOVE as $cls) {
        $paths = array_values(array_filter($byName[$cls] ?? [], fn ($p) => str_contains($p, '/Listeners/')));
        if (count($paths) === 1) $targets[$cls] = $paths[0];
        elseif (count($paths) === 0) out("  [تخطي]  $cls  (الملف مش موجود، يمكن انحذف من قبل)");
        else { out("  [تخطي]  $cls  (لقيت أكتر من ملف بنفس الاسم)"); $problems++; }
    }

    // فحص المراجع: ما نحذف listener إذا في ملف تاني بيذكره
    $alt = implode('|', array_map(fn ($c) => preg_quote($c, '/'), array_keys($targets)));
    $unsafe = [];
    if ($alt) {
        $skipPaths = array_flip(array_merge(array_values($targets), [str_replace('\\', '/', $provider)]));
        foreach (phpFiles($root, ['Modules', 'app', 'config', 'routes', 'tests', 'database', 'bootstrap']) as $f) {
            if (isset($skipPaths[$f])) continue;
            if (preg_match_all('/\b(' . $alt . ')\b/', file_get_contents($f), $mm)) {
                foreach (array_unique($mm[1]) as $cls) $unsafe[$cls][] = relPath($root, $f);
            }
        }
    }
    foreach ($unsafe as $cls => $files) {
        out("  [تخطي]  $cls  (مذكور بـ: " . implode(', ', array_slice($files, 0, 2)) . ")");
        unset($targets[$cls]);
        $problems++;
    }

    $safe = array_keys($targets);
    if ($safe) {
        $cleaned = cleanProviderContent(file_get_contents($provider), $safe);
        $left = [];
        foreach ($safe as $cls) if (preg_match('/\b' . preg_quote($cls, '/') . '\b/', $cleaned)) $left[] = $cls;
        if ($left) {
            out("  [فشل] ظلّت مراجع بالـ provider لـ: " . implode(', ', $left) . " — ما حذفت شي من الـ Listeners");
            $problems++;
        } elseif (writeChecked($provider, $cleaned, $bk, $dry)) {
            out("  [" . ($dry ? 'سيُعدَّل' : 'تم') . "]   " . relPath($root, $provider) . "  (شلت " . count($safe) . " listener)");
            foreach ($targets as $cls => $p) {
                if (!$dry) { $bk->save($p); unlink($p); }
            }
            out("  [" . ($dry ? 'سيُحذف' : 'انحذف') . "]   " . count($targets) . " ملف listener");
        } else {
            out("  [تراجع] EventServiceProvider (فشل php -l، رجعت النسخة القديمة، وما حذفت أي listener)");
            $problems++;
        }
    }

    out("\n== ملخص ==");
    out("بيضلوا متل ما هم (" . count(LISTENERS_TO_KEEP) . "): pivot / bulk insert — الـ trait ما بيغطيهم");
    out($problems ? "⚠ في $problems ملاحظة فوق، راجعها قبل ما تكمل" : "✓ بلا أي مشاكل");
    if (!$dry) out("نسخ احتياطية بـ: " . $bk->dir());
}

// ---------------------------------------------------------------- main

$cmd = $argv[1] ?? '';
$rootArg = $argv[2] ?? '.';
$dry = in_array('--dry-run', $argv, true);
$root = realpath($rootArg);
if (!$root || !is_dir("$root/Modules")) {
    fwrite(STDERR, "استخدام: php activity_cleanup.php <fix-options|academic> <project> [--dry-run]\nما لقيت مجلد Modules جوا: $rootArg\n");
    exit(1);
}
$root = str_replace('\\', '/', $root);
out(($dry ? "[معاينة فقط — ما رح يتغيّر شي]\n" : '') . "المشروع: $root\n");

match ($cmd) {
    'fix-options' => cmdFixOptions($root, $dry),
    'academic' => cmdAcademic($root, $dry),
    default => out("أمر غير معروف. استخدم: fix-options أو academic"),
};

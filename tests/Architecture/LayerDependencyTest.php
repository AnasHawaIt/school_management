<?php

/**
 * يفرض قاعدة الاعتماد بين الطبقات (القسم 4 بالوثيقة المعمارية).
 * كل موديول بيعتمد على اللي تحته فقط، عبر Contracts أو Events.
 *
 * مبني على بيانات (ALLOWED + DEBT) بدل أسطر يدوية مكررة، عشان نضمن
 * تغطية كل زوج موديولات بلا استثناء، وما نفوت أي حالة زي ما صار
 * بالمحاولات اليدوية السابقة.
 *
 * DEBT = الاعتماديات الممنوعة الموجودة فعلياً بالكود اليوم (دين تقني).
 * كل سطر فيها هدف لإزالته بمرحلة "كسر الاعتماد الدائري" من خطة الانتقال.
 * تم تحديثها بتاريخ 2026-10-03 بناءً على تقرير scan_dependencies.php الكامل.
 */

const ALL_MODULES = [
    'Academic', 'Activities', 'Announcement', 'Attendance', 'Core',
    'Examination', 'Finance', 'Library', 'Messagings', 'Notifications',
    'SMS', 'School', 'Transport',
];

// الاعتماديات المسموحة لكل موديول (القسم 10.1 بالوثيقة)
const ALLOWED = [
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

// الدين التقني الموجود فعلياً اليوم (مصدره: scan_dependencies.php بتاريخ 2026-10-03).
// أي سطر هون لازم ينحذف أول ما تنحل الحالة المقابلة إله بمرحلة كسر الاعتماد الدائري.
const DEBT = [
    'Academic'      => ['Activities', 'Attendance', 'Notifications', 'Transport'],
    'Activities'    => ['Notifications'],
    'Announcement'  => ['Library'],
    'Core'          => ['Academic', 'Library', 'Messagings', 'Notifications', 'SMS'],
    'Examination'   => ['Notifications', 'Messagings'], // اكتشف 2026-10-04، لازم يتفحص ليش
    'Library'       => ['Notifications'],
    'Messagings'    => ['Announcement'],
    'SMS'           => ['Announcement'],
    'School'        => ['Academic', 'Messagings', 'Notifications'],
    'Transport'     => ['Notifications'], // Library انحلت 2026-10-05 (كان Listener غلط مستورد من Library)
];

foreach (ALL_MODULES as $module) {
    $allowed = ALLOWED[$module] ?? [];
    $debt = DEBT[$module] ?? [];

    // ممنوع = كل الموديولات الأخرى، ناقص نفسه، ناقص المسموح فعلياً
    $forbidden = array_values(array_diff(ALL_MODULES, [$module], $allowed));

    if (empty($forbidden)) {
        continue; // ما في شي نمنعه (نادر، نظرياً ما بيصير)
    }

    $forbiddenNs = array_map(fn ($m) => "Modules\\{$m}", $forbidden);
    $debtNs = array_map(fn ($m) => "Modules\\{$m}", $debt);

    $test = arch("{$module} يعتمد فقط على الموديولات المسموحة له معمارياً")
        ->expect("Modules\\{$module}")
        ->not->toUse($forbiddenNs);

    if (!empty($debtNs)) {
        $test->ignoring($debtNs); // TODO: دين تقني، شوف تعليق DEBT أعلى الملف
    }
}

// ===== قاعدة إضافية: فقط Announcement و Messagings يعتمدوا على Notifications مباشرة =====
// (كل موديول ثاني لازم يستخدم NotifierInterface بدل الاستدعاء المباشر)
arch('فقط Announcement و Messagings يعتمدوا على Notifications مباشرة (الباقي لازم NotifierInterface)')
    ->expect('Modules\Notifications')
    ->toOnlyBeUsedIn([
        'Modules\Notifications', // Notifications نفسها (ملفاتها الداخلية ببعض)
        'Modules\Announcement',
        'Modules\Messagings',
        // دين تقني مؤقت، TODO: استبدلها كلها بـ NotifierInterface بالمرحلة ج
        'Modules\Core',
        'Modules\Academic',
        'Modules\Library',
        'Modules\Activities',
        'Modules\Examination',
        'Modules\Transport',
        'Modules\School',
        // دين تقني إضافي: app/Providers/AppServiceProvider.php (خارج أي موديول)
        // بيستورد مباشرة من Library, Messagings, Notifications, Transport.
        // TODO: انقل تسجيل الـ policies/morph map لكل موديول بـ Provider الخاص فيه.
        'App\Providers',
    ]);

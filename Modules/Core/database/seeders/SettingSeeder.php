<?php

namespace Modules\Core\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Entities\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'school_name_en',
                'value' => 'Elite International School',
                'type' => 'string',
                'description' => 'School name in English',
            ],
            [
                'key' => 'school_name_ar',
                'value' => 'مدرسة النخبة الدولية',
                'type' => 'string',
                'description' => 'School name in Arabic',
            ],
            [
                'key' => 'school_email',
                'value' => 'info@elite-school.com',
                'type' => 'string',
                'description' => 'Official school email',
            ],
            [
                'key' => 'school_logo',
                'value' => 'defaults/logo.png',
                'type' => 'string',
                'description' => 'Path to the school logo',
            ],
            [
                'key' => 'school_stamp',
                'value' => 'defaults/stamp.png',
                'type' => 'string',
                'description' => 'Official digital stamp for documents',
            ],
            [
                'key' => 'current_academic_year_id',
                'value' => '1',
                'type' => 'integer',
                'description' => 'ID of the active academic year',
            ],
            [
                'key' => 'attendance_mode',
                'value' => 'daily', // daily or lesson-based
                'type' => 'string',
                'description' => 'How to take attendance (Daily or by Subject)',
            ],
            [
                'key' => 'currency_symbol',
                'value' => '$',
                'type' => 'string',
                'description' => 'Currency used for school fees',
            ],
            [
                'key' => 'timezone',
                'value' => 'Asia/Damascus',
                'type' => 'string',
                'description' => 'School system timezone',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('School Settings seeded successfully!');
    }
}

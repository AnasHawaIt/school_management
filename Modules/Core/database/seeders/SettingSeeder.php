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
                'key' => 'school_name',
                'value' => 'My School',
                'type' => 'string',
                'description' => 'School name',
            ],
            [
                'key' => 'school_email',
                'value' => 'info@myschool.com',
                'type' => 'string',
                'description' => 'School email address',
            ],
            [
                'key' => 'school_phone',
                'value' => '1234567890',
                'type' => 'string',
                'description' => 'School phone number',
            ],
            [
                'key' => 'school_address',
                'value' => '123 Main Street, City, Country',
                'type' => 'string',
                'description' => 'School address',
            ],
            [
                'key' => 'academic_year',
                'value' => '2024-2025',
                'type' => 'string',
                'description' => 'Current academic year',
            ],
            [
                'key' => 'timezone',
                'value' => 'UTC',
                'type' => 'string',
                'description' => 'School timezone',
            ],
            [
                'key' => 'max_students_per_class',
                'value' => '30',
                'type' => 'integer',
                'description' => 'Maximum students per class',
            ],
            [
                'key' => 'enable_notifications',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Enable system notifications',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('Settings seeded successfully!');
    }
}

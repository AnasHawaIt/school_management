<?php

namespace Modules\Library\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Library\app\Entities\Author;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            [
                'name' => 'نجيب محفوظ',
                'description' => 'روائي وكاتب مصري.',
                'birth_date' => '1911-12-11',
                'death_date' => '2006-08-30',
            ],
            [
                'name' => 'غسان كنفاني',
                'description' => 'كاتب وروائي فلسطيني.',
                'birth_date' => '1936-04-09',
                'death_date' => '1972-07-08',
            ],
            [
                'name' => 'أحمد خالد توفيق',
                'description' => 'كاتب وطبيب وأديب مصري.',
                'birth_date' => '1962-06-10',
                'death_date' => '2018-04-02',
            ],
        ];

        foreach ($authors as $author) {
            Author::firstOrCreate(
                ['name' => $author['name']],
                [
                    'description' => $author['description'],
                    'birth_date' => $author['birth_date'],
                    'death_date' => $author['death_date'],
                ]
            );
        }
    }
}

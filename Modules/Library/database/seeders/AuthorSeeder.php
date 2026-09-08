<?php

namespace Modules\Library\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Library\Entities\Author;

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
            ['name' => 'توفيق الحكيم', 'description' => 'كاتب ومسرحي مصري.', 'birth_date' => '1898-10-09', 'death_date' => '1987-07-26'],
            ['name' => 'يوسف إدريس', 'description' => 'كاتب وطبيب مصري.', 'birth_date' => '1927-05-19', 'death_date' => '1991-08-01'],
            ['name' => 'الطيب صالح', 'description' => 'روائي سوداني بارز.', 'birth_date' => '1929-07-12', 'death_date' => '2009-02-18'],
            ['name' => 'إحسان عبد القدوس', 'description' => 'روائي وصحفي مصري.', 'birth_date' => '1919-01-01', 'death_date' => '1990-01-12'],
            ['name' => 'عبد الرحمن منيف', 'description' => 'روائي سعودي من أصول أردنية.', 'birth_date' => '1933-05-29', 'death_date' => '2004-01-24'],
            ['name' => 'إبراهيم نصر الله', 'description' => 'روائي وشاعر فلسطيني.', 'birth_date' => '1954-12-02', 'death_date' => null],
            ['name' => 'واسيني الأعرج', 'description' => 'روائي وأستاذ جزائري.', 'birth_date' => '1954-08-08', 'death_date' => null],
            ['name' => 'رضوى عاشور', 'description' => 'روائية وناقدة وأكاديمية مصرية.', 'birth_date' => '1946-05-26', 'death_date' => '2014-11-30'],
            ['name' => 'أمين معلوف', 'description' => 'روائي ومفكر لبناني فرنسي.', 'birth_date' => '1949-02-25', 'death_date' => null],
            ['name' => 'باولو كويلو', 'description' => 'روائي برازيلي.', 'birth_date' => '1947-08-24', 'death_date' => null],
            ['name' => 'جورج أورويل', 'description' => 'كاتب وصحفي إنجليزي.', 'birth_date' => '1903-06-25', 'death_date' => '1950-01-21'],
            ['name' => 'فيودور دوستويفسكي', 'description' => 'روائي وفيلسوف روسي.', 'birth_date' => '1821-11-11', 'death_date' => '1881-02-09'],
            ['name' => 'ليو تولستوي', 'description' => 'روائي وفيلسوف روسي.', 'birth_date' => '1828-09-09', 'death_date' => '1910-11-20'],
            ['name' => 'غابرييل غارسيا ماركيز', 'description' => 'روائي وصحفي كولومبي.', 'birth_date' => '1927-03-06', 'death_date' => '2014-04-17'],
            ['name' => 'جين أوستن', 'description' => 'روائية إنجليزية.', 'birth_date' => '1775-12-16', 'death_date' => '1817-07-18'],
            ['name' => 'دان براون', 'description' => 'روائي أمريكي متخصص في روايات الإثارة.', 'birth_date' => '1964-06-22', 'death_date' => null],
            ['name' => 'إلياس خوري', 'description' => 'روائي وناقد ومسرحي لبناني.', 'birth_date' => '1948-07-12', 'death_date' => '2023-09-15'],
            ['name' => 'ستيفن هوكينغ', 'description' => 'عالم فيزياء نظرية وعالم كونيات بريطاني.', 'birth_date' => '1942-01-08', 'death_date' => '2018-03-14'],
            ['name' => 'تشارلز داروين', 'description' => 'عالم طبيعة بريطاني وصاحب نظرية التطور.', 'birth_date' => '1809-02-12', 'death_date' => '1882-04-19'],
            ['name' => 'جيمس كلير', 'description' => 'كاتب ومحاضر في العادات وتطوير الأداء.', 'birth_date' => '1986-01-01', 'death_date' => null],
            ['name' => 'إكهارت تول', 'description' => 'كاتب ومتحدث في الوعي والتنمية الذاتية.', 'birth_date' => '1948-02-16', 'death_date' => null],
            ['name' => 'ستيفن كوفي', 'description' => 'كاتب ومحاضر أمريكي في القيادة والإدارة.', 'birth_date' => '1932-10-24', 'death_date' => '2012-07-16'],
            ['name' => 'ابن خلدون', 'description' => 'مؤرخ وعالم اجتماع وفيلسوف عربي.', 'birth_date' => '1332-05-27', 'death_date' => '1406-03-17'],
            ['name' => 'نيكولو مكيافيللي', 'description' => 'فيلسوف وكاتب ودبلوماسي إيطالي.', 'birth_date' => '1469-05-03', 'death_date' => '1527-06-21'],
            ['name' => 'سون تزو', 'description' => 'فيلسوف واستراتيجي عسكري صيني.', 'birth_date' => '0544-01-01', 'death_date' => '0496-01-01'],
            ['name' => 'توماس كورمن', 'description' => 'أستاذ وباحث في علوم الحاسوب والخوارزميات.', 'birth_date' => '1956-01-01', 'death_date' => null],
        ];

        foreach ($authors as $author) {
            Author::updateOrCreate(
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

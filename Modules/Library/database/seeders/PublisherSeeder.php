<?php

namespace Modules\Library\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Library\Entities\Publishers;

class PublisherSeeder extends Seeder
{
    public function run(): void
    {
        $publishers = [
            ['name' => 'دار الشروق'],
            ['name' => 'دار الآداب'],
            ['name' => 'المؤسسة العربية للدراسات والنشر'],
            ['name' => 'دار الهلال'],
            ['name' => 'دار الفكر'],
            ['name' => 'دار المعرفة'],
        ];

        foreach ($publishers as $publisher) {
            Publishers::firstOrCreate([
                'name' => $publisher['name'],
            ]);
        }
    }
}

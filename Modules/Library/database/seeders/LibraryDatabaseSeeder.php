<?php

namespace Modules\Library\database\seeders;

use Illuminate\Database\Seeder;

class LibraryDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AuthorSeeder::class,
            PublisherSeeder::class,
            CategorySeeder::class,
            BookSeeder::class,
            BookCopySeeder::class,
            MemberSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}

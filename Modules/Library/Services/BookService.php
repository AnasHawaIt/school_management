<?php

namespace Modules\Library\Services;

use Modules\Announcement\Entities\Announcement;
use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Announcement\Events\AnnouncementUpdated;
use Modules\Announcement\Events\AnnouncementDeleted;
use Modules\Core\Entities\ActivityLog;
use App\Models\User;
use Modules\Library\Entities\Book;

class BookService
{
    public function create(array $data)
    {
        return Book::create($data);
    }

    public function update($id, array $data)
    {
        $book = Book::findOrFail($id);
        $book->update($data);

        return $book;
    }

    public function delete($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return true;
    }

    public function getAll()
    {
        return Book::with(['author', 'category'])->get();
    }

}

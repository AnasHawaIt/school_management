<?php

namespace Modules\Library\Services;

use Modules\Library\Events\BookEvents\BookCreated;
use Modules\Library\Events\BookEvents\BookDeleted;
use Modules\Library\Events\BookEvents\BookUpdated;
use Modules\Library\Repositories\Interfaces\BookRepositoryInterface;

class BookService
{
    protected $repo;

    protected $locales = ['en', 'ar'];

    public function __construct(BookRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

//    private function prepareTranslatable(array $data, array $fields)
//    {
//        $result = [];
//
//        foreach ($fields as $field) {
//
//            if (!isset($data[$field])) {
//                continue;
//            }
//
//            foreach ($this->locales as $locale) {
//                $result[$field][$locale] = $data[$field][$locale] ?? null;
//            }
//        }
//
//        return $result;
//    }


    public function getBookOnlyTrashed()
    {
        return $this->repo->getBookOnlyTrashed();
    }

    public function restore($id)
    {
        return $this->repo->restore($id);
    }

    public function forceDelete($id)
    {
        return $this->repo->forceDelete($id);
    }

    public function getAll($request)
    {
        return $this->repo->getAll($request);
    }

    public function create(array $data)
    {
//
//        $translatable = $this->prepareTranslatable($data, [
//            'title',
//            'description'
//        ]);
//
//        $book= $this->repo->create([
//            ...$translatable,
//            'author_id'=>$data['author_id'],
//            'category_id'=>$data['category_id'],
//            'isbn'=>$data['isbn'],
//            'copies'=>$data['copies'],
//        ]);
        $book = $this->repo->create($data);

        event(new BookCreated($book, auth()->id()));

        return $book;
    }

    public function update($id, array $data)
    {
//        $translatable = $this->prepareTranslatable($data, [
//            'title',
//            'description'
//        ]);
//
//        $book= $this->repo->update($id,[
//            ...$translatable,
//            'author_id'=>$data['author_id'],
//            'category_id'=>$data['category_id'],
//            'isbn'=>$data['isbn'],
//            'copies'=>$data['copies'],
//        ]);

        $book = $this->repo->update($id, $data);

        event(new BookUpdated($book, auth()->id()));

        return $book;
    }

    public function delete($id)
    {
        $book = $this->repo->find($id);

        if (!$book) {
            throw new \Exception('Book not found');
        }

        $this->repo->delete($id);

        event(new BookDeleted($book, auth()->id()));

        return true;
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

}

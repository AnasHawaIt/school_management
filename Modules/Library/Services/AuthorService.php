<?php

namespace Modules\Library\Services;

use Modules\library\Events\AuthorEvents\AuthorCreated;
use Modules\library\Events\AuthorEvents\AuthorDeleted;
use Modules\library\Events\AuthorEvents\AuthorRestored;
use Modules\library\Events\AuthorEvents\AuthorUpdated;
use Modules\Library\Repositories\Interfaces\AuthorRepositoryInterface;

class AuthorService
{
    protected $repo;

    protected $locales = ['en', 'ar'];

    public function __construct(AuthorRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    private function prepareTranslatable(array $data, array $fields)
    {
        $result = [];

        foreach ($fields as $field) {

            if (!isset($data[$field])) {
                continue;
            }

            foreach ($this->locales as $locale) {
                $result[$field][$locale] = $data[$field][$locale] ?? null;
            }
        }

        return $result;
    }


    public function getAuthorOnlyTrashed()
    {
        return $this->repo->getAuthorOnlyTrashed();
    }

    public function restore($id)
    {
        $author= $this->repo->restore($id);

        event(new AuthorRestored($author));

        return $author;
    }

    public function forceDelete($id)
    {
        $author= $this->repo->forceDelete($id);

        event(new AuthorDeleted($author));

        return true;
    }

    public function getAll($request)
    {
        return $this->repo->getAll($request);
    }

    public function create(array $data)
    {

        $translatable = $this->prepareTranslatable($data, [
            'name',
            'description'
        ]);

        $author = $this->repo->create([
            $translatable,
            'birth_date' => $data['birth_date'] ?? null,
            'death_date' => $data['death_date'] ?? null,
        ]);

        event(new AuthorCreated($author, auth()->id()));

        return $author;
    }

    public function update($id, array $data)
    {
        $translatable = $this->prepareTranslatable($data, [
            'name',
            'description'
        ]);

        $author = $this->repo->update($id, [
            $translatable,
            'birth_date' => $data['birth_date'] ?? null,
            'death_date' => $data['death_date'] ?? null,
        ]);

        event(new AuthorUpdated($author, auth()->id()));

        return $author;
    }

    public function delete($id)
    {
        $author = $this->repo->find($id);

        if (!$author) {
            throw new \Exception('Author not found');
        }

        $this->repo->delete($id);

        event(new AuthorDeleted($author, auth()->id()));

        return true;
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

}

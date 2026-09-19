<?php

namespace Modules\Library\app\Services;

use App\Services\ImageService;
use Modules\Library\app\Events\AuthorEvents\AuthorCreated;
use Modules\Library\app\Events\AuthorEvents\AuthorDeleted;
use Modules\Library\app\Events\AuthorEvents\AuthorRestored;
use Modules\Library\app\Events\AuthorEvents\AuthorUpdated;
use Modules\Library\app\Repositories\Interfaces\AuthorRepositoryInterface;

class AuthorService
{
    protected $repo;

    protected $imageService;

    public function __construct(AuthorRepositoryInterface $repo, ImageService $imageService)
    {
        $this->repo = $repo;

        $this->imageService = $imageService;
    }

    public function getAuthorOnlyTrashed()
    {
        return $this->repo->getAuthorOnlyTrashed();
    }

    public function restore($id)
    {
        $author= $this->repo->restore($id);

        event(new AuthorRestored($author,auth()->id()));

        return $author;
    }

    public function forceDelete($id)
    {
        $author= $this->repo->find($id);

        $author->forceDelete();

        event(new AuthorDeleted($author,auth()->id()));

        return true;
    }

    public function getAll($request)
    {
        return $this->repo->getAll($request)->load('images');
    }

    public function create(array $data, $images = null)
    {
        $author = $this->repo->create($data);

        $this->imageService->upload($author, $images);

        event(new AuthorCreated($author, auth()->id()));

        return $author;
    }

    public function update($id, array $data, $images = null)
    {
        $this->repo->update($id, $data);

        $author = $this->repo->find($id);

        $this->imageService->replace($author, $images);

        event(new AuthorUpdated($author, auth()->id()));

        return $author;
    }

    public function delete($id)
    {
        $author = $this->repo->find($id);

        if (!$author) {
            throw new \Exception('Author not found');
        }

       $this->imageService->deleteAll($author);

        $this->repo->delete($id);

        event(new AuthorDeleted($author, auth()->id()));

        return true;
    }

    public function find($id)
    {
        return $this->repo->find($id)->load('images');
    }

}

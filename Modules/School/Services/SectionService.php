<?php

namespace Modules\School\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\School\Contracts\Repositories\SectionRepositoryInterface;
use Modules\School\Contracts\Services\SectionServiceInterface;
use Modules\School\Entities\Section;
use Modules\School\Events\SectionCreated;
use Modules\School\Events\SectionUpdated;
use Modules\School\Events\SectionDeleted;

class SectionService implements SectionServiceInterface
{
    protected SectionRepositoryInterface $repository;

    public function __construct(
        SectionRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Get all active sections.
     */
    public function getAll(): Collection
    {
        return $this->repository->getActive();
    }

    /**
     * Get section by ID.
     */
    public function getById(int $id): ?Section
    {
        return $this->repository->find($id);
    }

    /**
     * Create new section.
     */
    public function create(array $data): Section
    {
        $section = DB::transaction(function () use ($data) {

            if (!isset($data['current_students'])) {
                $data['current_students'] = 0;
            }

            return $this->repository->create($data);
        });

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        event(new SectionCreated($section));

        return $section;
    }

    /**
     * Update section.
     */
    public function update(int $id, array $data): bool
    {
        $updated = DB::transaction(function () use ($id, $data) {

            return $this->repository->update(
                $id,
                $data
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        if ($updated) {

            $section = $this->repository->find($id);

            if ($section) {
                event(new SectionUpdated($section));
            }
        }

        return $updated;
    }

    /**
     * Delete section.
     */
    public function delete(int $id): bool
    {
        /*
        |--------------------------------------------------------------------------
        | Get section before delete
        |--------------------------------------------------------------------------
        */

        $section = $this->repository->find($id);

        if (!$section) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $deleted = DB::transaction(function () use ($id) {

            return $this->repository->delete($id);
        });

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        if ($deleted) {
            event(new SectionDeleted($section));
        }

        return $deleted;
    }

    /**
     * Get sections by class.
     */
    public function getByClass(int $classId): Collection
    {
        return $this->repository->getByClass($classId);
    }
}

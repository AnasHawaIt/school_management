<?php

namespace Modules\Academic\Services;

use Illuminate\Support\Facades\Auth;

use Modules\Academic\Contracts\Services\InspectionProgramServiceInterface;
use Modules\Academic\Contracts\Repositories\InspectionProgramRepositoryInterface;
use Modules\Academic\Entities\InspectionProgram;
use Modules\Academic\Events\InspectionProgramEvents\CounselorAssignedToInspectionProgram;
use Modules\Academic\Events\InspectionProgramEvents\CounselorUnassignedFromInspectionProgram;
use Modules\Academic\Events\InspectionProgramEvents\InspectionProgramCreated;
use Modules\Academic\Events\InspectionProgramEvents\InspectionProgramDeleted;
use Modules\Academic\Events\InspectionProgramEvents\InspectionProgramRestored;
use Modules\Academic\Events\InspectionProgramEvents\InspectionProgramSetCurrent;
use Modules\Academic\Events\InspectionProgramEvents\InspectionProgramStatusUpdated;
use Modules\Academic\Events\InspectionProgramEvents\InspectionProgramUpdated;
use Modules\Academic\Events\InspectionProgramEvents\ObservationSubmitted;


class InspectionProgramService implements InspectionProgramServiceInterface
{
    protected $repository;

    public function __construct(
        InspectionProgramRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /*
    |--------------------------------------------------------------------------
    | Queries
    |--------------------------------------------------------------------------
    */

    public function getAll(array $filters = [])
    {
        return $this->repository->getAll($filters);
    }

    public function getProgram(int $id)
    {
        return $this->repository->findById($id);
    }

    public function getSectionPrograms(
        int $sectionId,
        array $filters = []
    ) {
        return $this->repository->getBySection(
            $sectionId,
            $filters
        );
    }

    public function getCounselorPrograms(
        int $counselorId,
        array $filters = []
    ) {
        return $this->repository->getByCounselor(
            $counselorId,
            $filters
        );
    }

    public function getCurrentCounselorProgram(
        int $counselorId
    ) {
        return $this->repository->getCurrentCounselorProgram(
            $counselorId
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CRUD
    |--------------------------------------------------------------------------
    */

    public function createProgram(array $data): InspectionProgram
    {
        $userId = Auth::id();

        $data['created_by'] = $userId;

        /*
         * Remove counselors before creating the program
         * because counselors are assigned separately.
         */
        $counselors = $data['counselors'] ?? [];

        unset($data['counselors']);

        $program = $this->repository->create($data);

        /*
         * Assign counselors
         */
        foreach ($counselors as $counselor) {

            $role = $counselor['role'] ?? 'member';

            $this->repository->assignCounselor(
                $program->id,
                $counselor['counselor_id'],
                $role
            );

            event(new CounselorAssignedToInspectionProgram(
                $program,
                $counselor['counselor_id'],
                $role,
                $userId
            ));
        }

        /*
         * Program created event
         */
        event(new InspectionProgramCreated(
            $program,
            $userId
        ));

        return $program->load([
            'section.class.grade',
            'counselors.user',
            'semester',
        ]);
    }

    public function updateProgram(
        int $id,
        array $data
    ): InspectionProgram {
        $program = $this->repository->findById($id);

        if (!$program) {
            throw new \Exception(
                'Inspection program not found.'
            );
        }

        if ($program->status === 'completed') {
            throw new \Exception(
                'Cannot edit a completed inspection program.'
            );
        }

        /*
         * Keep the original values before update.
         */
        $oldData = $program->getAttributes();

        $program = $this->repository->update(
            $id,
            $data
        );

        /*
         * Calculate changes.
         */
        $changes = [];

        foreach ($data as $key => $value) {
            if (
                array_key_exists($key, $oldData)
                && $oldData[$key] != $value
            ) {
                $changes[$key] = [
                    'old' => $oldData[$key],
                    'new' => $value,
                ];
            }
        }

        event(new InspectionProgramUpdated(
            $program,
            $changes,
            Auth::id()
        ));

        return $program;
    }

    public function deleteProgram(int $id): bool
    {
        $program = $this->repository->findById($id);

        if (!$program) {
            throw new \Exception(
                'Inspection program not found.'
            );
        }

        $result = $this->repository->delete($id);

        if ($result) {
            event(new InspectionProgramDeleted(
                $program,
                Auth::id()
            ));
        }

        return $result;
    }

    public function restoreProgram(int $id): bool
    {
        $result = $this->repository->restore($id);

        if ($result) {

            $program = $this->repository->findById($id);

            event(new InspectionProgramRestored(
                $program,
                Auth::id()
            ));
        }

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | Counselors
    |--------------------------------------------------------------------------
    */

    public function assignCounselor(
        int $programId,
        int $counselorId,
        string $role
    ): bool {
        $result = $this->repository->assignCounselor(
            $programId,
            $counselorId,
            $role
        );

        if ($result) {

            $program = $this->repository->findById(
                $programId
            );

            event(new CounselorAssignedToInspectionProgram(
                $program,
                $counselorId,
                $role,
                Auth::id()
            ));
        }

        return $result;
    }

    public function unassignCounselor(
        int $programId,
        int $counselorId
    ): bool {
        $result = $this->repository->unassignCounselor(
            $programId,
            $counselorId
        );

        if ($result) {

            $program = $this->repository->findById(
                $programId
            );

            event(new CounselorUnassignedFromInspectionProgram(
                $program,
                $counselorId,
                Auth::id()
            ));
        }

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | Observation
    |--------------------------------------------------------------------------
    */

    public function submitObservation(
        int $programId,
        int $counselorId,
        array $data
    ): bool {
        $result = $this->repository->updateCounselorObservation(
            $programId,
            $counselorId,
            $data
        );

        if ($result) {

            $program = $this->repository->findById(
                $programId
            );

            event(new ObservationSubmitted(
                $program,
                $counselorId,
                $data,
                Auth::id()
            ));
        }

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        int $id,
        string $status
    ): InspectionProgram {
        $program = $this->repository->findById($id);

        if (!$program) {
            throw new \Exception(
                'Inspection program not found.'
            );
        }

        $oldStatus = $program->status;

        /*
         * Don't create an event if the status
         * is actually unchanged.
         */
        if ($oldStatus === $status) {
            return $program;
        }

        $program = $this->repository->updateStatus(
            $id,
            $status
        );

        event(new InspectionProgramStatusUpdated(
            $program,
            $oldStatus,
            $status,
            Auth::id()
        ));

        return $program;
    }

    /*
    |--------------------------------------------------------------------------
    | Current Program
    |--------------------------------------------------------------------------
    */

    public function setCurrent(int $id): bool
    {
        $program = $this->repository->findById($id);

        if (!$program) {
            throw new \Exception(
                'Inspection program not found.'
            );
        }

        $result = $this->repository->setCurrent($id);

        if ($result) {

            $program = $program->fresh();

            event(new InspectionProgramSetCurrent(
                $program,
                Auth::id()
            ));
        }

        return $result;
    }
}

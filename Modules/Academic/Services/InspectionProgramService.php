<?php

namespace Modules\Academic\Services;

use Modules\Academic\Contracts\Services\InspectionProgramServiceInterface;
use Modules\Academic\Contracts\Repositories\InspectionProgramRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class InspectionProgramService implements InspectionProgramServiceInterface
{
    protected $repository;

    public function __construct( InspectionProgramRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(array $filters = [])
    {
        return $this->repository->getAll($filters);
    }

    public function getProgram(int $id)
    {
        return $this->repository->findById($id);
    }

    public function createProgram(array $data): object
    {
        $data['created_by'] = Auth::id();
        $program = $this->repository->create($data);

        if (!empty($data['counselors'])) {
            foreach ($data['counselors'] as $c) {
                $this->repository->assignCounselor(
                    $program->id,
                    $c['counselor_id'],
                    $c['role'] ?? 'member'
                );
            }
        }

        return $program->load(['section.class.grade', 'counselors.user', 'semester']);
    }

    public function updateProgram(int $id, array $data): object
    {
        $program = $this->repository->findById($id);

        if ($program->status === 'completed') {
            throw new \Exception('Cannot edit a completed inspection program.');
        }

        return $this->repository->update($id, $data);
    }

    public function deleteProgram(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function restoreProgram(int $id): bool
    {
        return $this->repository->restore($id);
    }

    public function assignCounselor(int $programId, int $counselorId, string $role): bool
    {
        return $this->repository->assignCounselor($programId, $counselorId, $role);
    }

    public function unassignCounselor(int $programId, int $counselorId): bool
    {
        return $this->repository->unassignCounselor($programId, $counselorId);
    }

    public function submitObservation(int $programId, int $counselorId, array $data): bool
    {
        return $this->repository->updateCounselorObservation($programId, $counselorId, $data);
    }

    public function updateStatus(int $id, string $status): object
    {
        return $this->repository->updateStatus($id, $status);
    }

    public function getSectionPrograms(int $sectionId, array $filters = [])
    {
        return $this->repository->getBySection($sectionId, $filters);
    }

    public function getCounselorPrograms(int $counselorId, array $filters = [])
    {
        return $this->repository->getByCounselor($counselorId, $filters);
    }
    public function setCurrent(int $id): bool
    {
        $program = $this->repository->findById($id);

        if (!$program) {
            throw new \Exception('Inspection program not found.');
        }

        return $this->repository->setCurrent($id);
    }
    public function getCurrentCounselorProgram(int $counselorId)
    {
        return $this->repository->getCurrentCounselorProgram($counselorId);
    }
}

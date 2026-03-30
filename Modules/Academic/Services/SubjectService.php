<?php

namespace Modules\Academic\Services;

use Modules\Academic\Contracts\Services\SubjectServiceInterface;
use Modules\Academic\Contracts\Repositories\SubjectRepositoryInterface;

class SubjectService implements SubjectServiceInterface
{
    public function __construct(
        protected SubjectRepositoryInterface $subjectRepository,
    ) {}

    public function getAllSubjects(array $filters = [])
    {
        return $this->subjectRepository->getAll($filters);
    }

    public function getSubject(int $id)
    {
        return $this->subjectRepository->findById($id);
    }

    public function createSubject(array $data): object
    {
        return $this->subjectRepository->create($data);
    }

    public function updateSubject(int $id, array $data): object
    {
        return $this->subjectRepository->update($id, $data);
    }

    public function deleteSubject(int $id): bool
    {
        return $this->subjectRepository->delete($id);
    }

    public function restoreSubject(int $id): bool
    {
        return $this->subjectRepository->restore($id);
    }

    public function getSubjectsByGrade(int $gradeId)
    {
        return $this->subjectRepository->getByGrade($gradeId);
    }

    public function assignTeacher(int $subjectId, int $teacherId, int $sectionId, int $academicYearId): bool
    {
        return $this->subjectRepository->assignTeacher($subjectId, $teacherId, $sectionId, $academicYearId);
    }

    public function unassignTeacher(int $subjectId, int $teacherId, int $sectionId, int $academicYearId): bool
    {
        return $this->subjectRepository->unassignTeacher($subjectId, $teacherId, $sectionId, $academicYearId);
    }

    public function getSubjectWithTeachers(int $id)
    {
        return $this->subjectRepository->getWithTeachers($id);
    }

    public function toggleStatus(int $id): object
    {
        $subject   = $this->subjectRepository->findById($id);
        $newStatus = $subject->status === 'active' ? 'inactive' : 'active';
        return $this->subjectRepository->update($id, ['status' => $newStatus]);
    }
}

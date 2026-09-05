<?php

namespace Modules\Academic\Services;

use Illuminate\Support\Facades\Auth;

use Modules\Academic\Contracts\Services\SubjectServiceInterface;
use Modules\Academic\Contracts\Repositories\SubjectRepositoryInterface;
use Modules\Academic\Entities\Subject;
use Modules\Academic\Events\SubjectsEvents\SubjectCreated;
use Modules\Academic\Events\SubjectsEvents\SubjectDeleted;
use Modules\Academic\Events\SubjectsEvents\SubjectRestored;
use Modules\Academic\Events\SubjectsEvents\SubjectUpdated;
use Modules\Academic\Events\SubjectsEvents\TeacherAssignedToSubject;
use Modules\Academic\Events\SubjectsEvents\TeacherUnassignedFromSubject;


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

    public function createSubject(array $data): Subject
    {
        $subject = $this->subjectRepository->create($data);

        event(new SubjectCreated(
            $subject,
            Auth::id()
        ));

        return $subject;
    }

    public function updateSubject(int $id, array $data): Subject
    {
        $subject = $this->subjectRepository->findById($id);

        $subject = $this->subjectRepository->update($id, $data);

        event(new SubjectUpdated(
            $subject,
            $subject->getChanges(),
            Auth::id()
        ));

        return $subject;
    }

    public function deleteSubject(int $id): bool
    {
        $subject = $this->subjectRepository->findById($id);

        $result = $this->subjectRepository->delete($id);

        if ($result) {
            event(new SubjectDeleted(
                $subject,
                Auth::id()
            ));
        }

        return $result;
    }

    public function restoreSubject(int $id): bool
    {
        $result = $this->subjectRepository->restore($id);

        if ($result) {
            $subject = $this->subjectRepository->findById($id);

            event(new SubjectRestored(
                $subject,
                Auth::id()
            ));
        }

        return $result;
    }

    public function getSubjectsByGrade(int $gradeId)
    {
        return $this->subjectRepository->getByGrade($gradeId);
    }

    public function assignTeacher(
        int $subjectId,
        int $teacherId,
        int $sectionId,
        int $academicYearId
    ): bool {

        $result = $this->subjectRepository->assignTeacher(
            $subjectId,
            $teacherId,
            $sectionId,
            $academicYearId
        );

        if ($result) {
            $subject = $this->subjectRepository->findById($subjectId);

            event(new TeacherAssignedToSubject(
                $subject,
                $teacherId,
                $sectionId,
                $academicYearId,
                Auth::id()
            ));
        }

        return $result;
    }

    public function unassignTeacher(
        int $subjectId,
        int $teacherId,
        int $sectionId,
        int $academicYearId
    ): bool {

        $result = $this->subjectRepository->unassignTeacher(
            $subjectId,
            $teacherId,
            $sectionId,
            $academicYearId
        );

        if ($result) {
            $subject = $this->subjectRepository->findById($subjectId);

            event(new TeacherUnassignedFromSubject(
                $subject,
                $teacherId,
                $sectionId,
                $academicYearId,
                Auth::id()
            ));
        }

        return $result;
    }

    public function getSubjectWithTeachers(int $id)
    {
        return $this->subjectRepository->getWithTeachers($id);
    }

    public function toggleStatus(int $id): Subject
    {
        $subject = $this->subjectRepository->findById($id);

        $newStatus = $subject->status === 'active'
            ? 'inactive'
            : 'active';

        $subject = $this->subjectRepository->update(
            $id,
            ['status' => $newStatus]
        );

        event(new SubjectUpdated(
            $subject,
            $subject->getChanges(),
            Auth::id()
        ));

        return $subject;
    }
}

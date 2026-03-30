<?php

namespace Modules\Academic\Services;

use Modules\Academic\Contracts\Services\StudentServiceInterface;
use Modules\Academic\Contracts\Repositories\StudentRepositoryInterface;
use Modules\Academic\Entities\StudentMedicalRecord;
use Modules\School\Entities\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class StudentService implements StudentServiceInterface
{
    public function __construct(
        protected StudentRepositoryInterface $studentRepository,
    ) {}

    public function getAllStudents(array $filters = [])
    {
        return $this->studentRepository->getAll($filters);
    }

    public function getStudent(int $id)
    {
        return $this->studentRepository->findById($id);
    }

    public function createStudent(array $data): object
    {
        return DB::transaction(function () use ($data) {
            if (!empty($data['current_section_id'])) {
                $section = Section::findOrFail($data['current_section_id']);
                if ($section->current_students >= $section->max_students) {
                    throw new \Exception('Section is full. Maximum capacity reached.');
                }
                $section->increment('current_students');
            }

            $user = User::create([
                'name'     => "{$data['first_name']} {$data['last_name']}",
                'email'    => $data['email'],
                'password' => Hash::make($data['password'] ?? 'Student@123'),
                'type'     => 'student',
            ]);

            $data['user_id']    = $user->id;
            $data['student_id'] = $this->studentRepository->generateStudentId();

            $student = $this->studentRepository->create($data);

            if (!empty($data['medical_record'])) {
                StudentMedicalRecord::create(
                    array_merge($data['medical_record'], ['student_id' => $student->id])
                );
            }

            $user->assignRole('student');

            return $student->load(['user', 'section.class.grade', 'academicYear']);
        });
    }

    public function updateStudent(int $id, array $data): object
    {
        return DB::transaction(function () use ($id, $data) {
            $student    = $this->studentRepository->update($id, $data);
            $userUpdate = [];

            if (!empty($data['email'])) {
                $userUpdate['email'] = $data['email'];
                $userUpdate['name']  = ($data['first_name'] ?? $student->first_name)
                    . ' ' . ($data['last_name'] ?? $student->last_name);
            }
            if (!empty($data['password'])) {
                $userUpdate['password'] = Hash::make($data['password']);
            }
            if (!empty($userUpdate)) {
                $student->user->update($userUpdate);
            }

            return $student;
        });
    }

    public function deleteStudent(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $student = $this->studentRepository->findById($id);
            if ($student->current_section_id) {
                $student->section()->decrement('current_students');
            }
            $student->user->update(['status' => 'inactive']);
            return $this->studentRepository->delete($id);
        });
    }

    public function restoreStudent(int $id): bool
    {
        return $this->studentRepository->restore($id);
    }

    public function transferSection(int $studentId, int $newSectionId): bool
    {
        return DB::transaction(function () use ($studentId, $newSectionId) {
            $section = Section::findOrFail($newSectionId);
            if ($section->current_students >= $section->max_students) {
                throw new \Exception('Target section is full.');
            }
            return $this->studentRepository->transferSection($studentId, $newSectionId);
        });
    }

    public function promoteStudents(int $fromSectionId, int $toSectionId): array
    {
        return DB::transaction(function () use ($fromSectionId, $toSectionId) {
            $count = $this->studentRepository->promoteStudents($fromSectionId, $toSectionId);
            return [
                'promoted_count' => $count,
                'from_section'   => $fromSectionId,
                'to_section'     => $toSectionId,
            ];
        });
    }

    public function getStudentWithParents(int $id)
    {
        return $this->studentRepository->getWithParents($id);
    }

    public function getStudentWithMedicalRecord(int $id)
    {
        return $this->studentRepository->getWithMedicalRecord($id);
    }

    public function updateMedicalRecord(int $studentId, array $data): object
    {
        $this->studentRepository->findById($studentId); // ensure exists
        return StudentMedicalRecord::updateOrCreate(['student_id' => $studentId], $data);
    }

    public function getStudentsBySection(int $sectionId)
    {
        return $this->studentRepository->getBySection($sectionId);
    }

    public function getSectionStats(int $sectionId): array
    {
        return $this->studentRepository->getStatsBySection($sectionId);
    }

    public function toggleStatus(int $id, string $status): object
    {
        return $this->studentRepository->update($id, ['status' => $status]);
    }
}

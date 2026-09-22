<?php

namespace App\Services;

use App\Contracts\Repositories\StudentRepositoryInterface;
use App\Contracts\Services\StudentServiceInterface;
use App\Entities\Student;
use App\Entities\StudentMedicalRecord;
use App\Events\StudentEvents\StudentAssignedToSection;
use App\Events\StudentEvents\StudentCreated;
use App\Events\StudentEvents\StudentDeleted;
use App\Events\StudentEvents\StudentPromoted;
use App\Events\StudentEvents\StudentRestored;
use App\Events\StudentEvents\StudentStatusUpdated;
use App\Events\StudentEvents\StudentTransferred;
use App\Events\StudentEvents\StudentUpdated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Academic\app\Events\StudentEvents\MedicalRecordUpdated;
use Modules\Core\app\Entities\User;
use Modules\School\Entities\Section;

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

    public function createStudent(array $data): Student
    {
        return DB::transaction(function () use ($data) {
            if (!empty($data['current_section_id'])) {
                $section = Section::findOrFail(
                    $data['current_section_id']
                );

                if ($section->current_students >= $section->max_students) {
                    throw new \Exception(
                        'Section is full. Maximum capacity reached.'
                    );
                }
                $section->increment('current_students');
            }

            $user = User::create([
                'first_name'     => $data['first_name'],
                'last_name'      => $data['last_name'],
                'first_name_ar'  => $data['first_name_ar'] ?? null,
                'last_name_ar'   => $data['last_name_ar'] ?? null,
                'gender'         => $data['gender'],
                'date_of_birth'  => $data['date_of_birth'] ?? null,
                'phone'          => $data['phone'] ?? null,
                'email'          => $data['email'],
                'password'       => Hash::make(
                    $data['password'] ?? 'Student@123'
                ),
                'user_type'      => 'student',
                'is_active'      => true,
            ]);

            $data['user_id'] = $user->id;

            $data['student_id'] =
                $this->studentRepository->generateStudentId();

            $student = $this->studentRepository->create($data);

            if (!empty($data['medical_record'])) {
                StudentMedicalRecord::create(
                    array_merge(
                        $data['medical_record'],
                        [
                            'student_id' => $student->id,
                        ]
                    )
                );
            }

            $user->assignRole('student');

            event(new StudentCreated(
                $student,
                Auth::id()
            ));

            return $student->load([
                'user',
                'section.class.grade',
                'academicYear',
            ]);
        });
    }

    public function updateStudent(int $id, array $data): Student
    {
        return DB::transaction(function () use ($id, $data) {
            $student = $this->studentRepository->findById($id);

            $oldStudentData = $student->getAttributes();

            $student = $this->studentRepository->update(
                $id,
                $data
            );
            $userUpdate = [];

            if (array_key_exists('email', $data)) {
                $userUpdate['email'] = $data['email'];
                if (array_key_exists('first_name', $data)) {
                    $userUpdate['first_name'] = $data['first_name'];
                }

                if (array_key_exists('last_name', $data)) {
                    $userUpdate['last_name'] = $data['last_name'];
                }
            }

                if (!empty($data['password'])) {
                    $userUpdate['password'] = Hash::make(
                        $data['password']
                    );
                }

            $userChanges = [];


            if (!empty($userUpdate)) {

                $user = $student->user;

                $oldUserData = $user->getAttributes();

                $user->update($userUpdate);

                $userChanges = $user->getChanges();
            }

            event(new StudentUpdated(
                $student,
                [
                    'student' => $student->getChanges(),
                    'user'    => $userChanges,
                ],
                Auth::id()
            ));

            return $student->load('user');

        });
    }

    public function deleteStudent(int $id): bool
    {
        return DB::transaction(function () use ($id) {

            $student = $this->studentRepository->findById($id);
            if ($student->current_section_id) {
                $student->section()
                    ->decrement('current_students');
            }
            $student->user->update([
                'is_active' => false,
            ]);

            $result = $this->studentRepository->delete($id);

            if ($result) {

                event(new StudentDeleted(
                    $student,
                    Auth::id()
                ));
            }

            return $result;
        });
    }

    public function restoreStudent(int $id): bool
    {
        return DB::transaction(function () use ($id) {

            $student = $this->studentRepository
                ->findById($id);

            $result = $this->studentRepository
                ->restore($id);

            if ($result) {

                $student->user->update([
                    'is_active' => true,
                ]);

                event(new StudentRestored(
                    $student->fresh(),
                    Auth::id()
                ));
            }

            return $result;
        });
    }

    public function transferSection(
        int $studentId,
        int $newSectionId
    ): bool {

        return DB::transaction(function () use (
            $studentId,
            $newSectionId
        ) {

            $student = $this->studentRepository
                ->findById($studentId);

            $oldSectionId = $student->current_section_id;
            $section = Section::findOrFail($newSectionId);

            if (
                $section->current_students >=
                $section->max_students
            ) {
                throw new \Exception(
                    'Target section is full.'
                );
            }

                $result = $this->studentRepository
                    ->transferSection(
                        $studentId,
                        $newSectionId
                    );

                if ($result) {

                    event(new StudentTransferred(
                        $student->fresh(),
                        $oldSectionId,
                        $newSectionId,
                        Auth::id()
                    ));
                }

                return $result;
        });
    }

    public function promoteStudents(
        int $fromSectionId,
        int $toSectionId
    ): array {

        return DB::transaction(function () use (
            $fromSectionId,
            $toSectionId
        ) {

            $count = $this->studentRepository
                ->promoteStudents(
                    $fromSectionId,
                    $toSectionId
                );

            if ($count > 0) {

                event(new StudentPromoted(
                    $fromSectionId,
                    $toSectionId,
                    $count,
                    Auth::id()
                ));
            }
            return [
                'promoted_count' => $count,
                'from_section'   => $fromSectionId,
                'to_section'     => $toSectionId,
            ];
        });
    }

    public function getStudentWithParents(int $id)
    {
        return $this->studentRepository
            ->getWithParents($id);
    }

    public function getStudentWithMedicalRecord(int $id)
    {
        return $this->studentRepository
            ->getWithMedicalRecord($id);
    }

    public function updateMedicalRecord(
        int $studentId,
        array $data
    ): object {

        $student = $this->studentRepository
            ->findById($studentId);

        $medicalRecord = StudentMedicalRecord::updateOrCreate(
            [
                'student_id' => $studentId,
            ],
            $data
        );

        event(new MedicalRecordUpdated(
            $student->fresh(),
            $medicalRecord->getChanges(),
            Auth::id()
        ));

        return $medicalRecord;
    }

    public function getStudentsBySection(int $sectionId)
    {
        return $this->studentRepository
            ->getBySection($sectionId);
    }

    public function getSectionStats(int $sectionId): array
    {
        return $this->studentRepository
            ->getStatsBySection($sectionId);
    }

    public function toggleStatus(
        int $id,
        string $status
    ): Student {

        $student = $this->studentRepository
            ->findById($id);

        $oldStatus = $student->status;

        $student = $this->studentRepository
            ->update($id, [
                'status' => $status,
            ]);

        if ($oldStatus !== $status) {

            event(new StudentStatusUpdated(
                $student,
                $oldStatus,
                $status,
                Auth::id()
            ));
        }

        return $student;
    }

    public function assignStudentToSection(
        int $sectionId,
        int $studentId,
        int $semesterId,
        int $academicYearId
    ): bool {

        return DB::transaction(function () use (
            $sectionId,
            $studentId,
            $semesterId,
            $academicYearId
        ) {

            $student = $this->studentRepository
                ->findById($studentId);
            $section = Section::findOrFail($sectionId);

            if (
                $section->current_students >=
                $section->max_students
            ) {
                throw new \Exception(
                    'Section is full. Maximum capacity reached.'
                );
             }

            $assigned = $this->studentRepository
                ->assignStudent(
                    $sectionId,
                    $studentId,
                    $semesterId,
                    $academicYearId
                );

            if ($assigned) {
                $section->increment('current_students');

                $this->studentRepository->update(
                    $studentId,
                    [
                        'current_section_id' => $sectionId,
                    ]
                );

                $section->increment(
                    'current_students'
                );

                /*
                 * Event
                 */
                event(new StudentAssignedToSection(
                    $student->fresh(),
                    $sectionId,
                    $semesterId,
                    $academicYearId,
                    Auth::id()
                ));
            }

            return $assigned;
        });
    }
}

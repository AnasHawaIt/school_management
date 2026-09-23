<?php

namespace Modules\Academic\app\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Academic\app\Contracts\Repositories\TeacherRepositoryInterface;
use Modules\Academic\app\Contracts\Services\TeacherServiceInterface;
use Modules\Academic\app\Entities\Teacher;
use Modules\Academic\app\Entities\TeacherQualification;
use Modules\Academic\app\Events\TeacherEvents\QualificationAdded;
use Modules\Academic\app\Events\TeacherEvents\QualificationDeleted;
use Modules\Academic\app\Events\TeacherEvents\TeacherCreated;
use Modules\Academic\app\Events\TeacherEvents\TeacherDeleted;
use Modules\Academic\app\Events\TeacherEvents\TeacherRestored;
use Modules\Academic\app\Events\TeacherEvents\TeacherStatusToggled;
use Modules\Academic\app\Events\TeacherEvents\TeacherUpdated;
use Modules\Core\app\Entities\User;

class TeacherService implements TeacherServiceInterface
{
    public function __construct(
        protected TeacherRepositoryInterface $teacherRepository,
    ) {}

    public function getAllTeachers(array $filters = [])
    {
        return $this->teacherRepository->getAll($filters);
    }

    public function getTeacher(int $id)
    {
        return $this->teacherRepository->findById($id);
    }
    public function getTeacherWithQualifications(int $id)
    {
        return $this->teacherRepository->getWithQualifications($id);
    }

    public function getTeacherTimetable(
        int $teacherId,
        int $semesterId
    ) {
        return $this->teacherRepository
            ->getTeacherTimetable($teacherId, $semesterId);
    }

    public function createTeacher(array $data): Teacher
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'first_name'    => $data['first_name'],
                'last_name'     => $data['last_name'],
                'first_name_ar' => $data['first_name_ar'],
                'last_name_ar'  => $data['last_name_ar'],
                'gender'        => $data['gender'],
                'date_of_birth' => $data['date_of_birth'],
                'phone'         => $data['phone'],
                'avatar'        => $data['avatar'] ?? null,
                'email'         => $data['email'],
                'password'      => Hash::make(
                    $data['password'] ?? 'Teacher@123'
                ),
                'user_type'     => 'teacher',
            ]);

            $data['user_id']     = $user->id;
            $data['employee_id'] =
                $this->teacherRepository->generateEmployeeId();

            $teacher = $this->teacherRepository->create($data);

            if (!empty($data['qualifications'])) {

                foreach ($data['qualifications'] as $qualification) {

                    TeacherQualification::create([
                        ...$qualification,
                        'teacher_id' => $teacher->id,
                    ]);
                }
            }

            $user->assignRole('teacher');

            event(new TeacherCreated(
                $teacher,
                Auth::id()
            ));

            return $teacher->load([
                'user',
                'qualifications',
            ]);
        });
    }

    public function updateTeacher(int $id, array $data): Teacher
    {
        return DB::transaction(function () use ($id, $data) {

            $teacher = $this->teacherRepository->findById($id);

            $teacher->fill($data);

            $changes = $teacher->getDirty();

            $teacher->save();

            $userUpdate = [];

            if (isset($data['first_name'])) {
                $userUpdate['first_name'] = $data['first_name'];
            }

            if (isset($data['last_name'])) {
                $userUpdate['last_name'] = $data['last_name'];
            }

            if (isset($data['password'])) {
                $userUpdate['password'] = Hash::make(
                    $data['password']
                );
            }

            if (isset($userUpdate)) {
                $teacher->user->update($userUpdate);
            }

            event(new TeacherUpdated(
                $teacher->fresh(),
                $changes,
                Auth::id()
            ));

            return $teacher->fresh([
                'user',
                'qualifications',
            ]);
        });
    }

    public function deleteTeacher(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $teacher = $this->teacherRepository
                ->findById($id);

            $teacher->user->update([
                'status' => 'inactive',
            ]);

            $result = $this->teacherRepository
                ->delete($id);

            if ($result) {

                event(new TeacherDeleted(
                    $teacher,
                    Auth::id()
                ));
            }

            return $result;
        });
    }

    public function restoreTeacher(int $id): bool
    {
        $teacher = $this->teacherRepository
            ->findById($id);

        $result = $this->teacherRepository
            ->restore($id);
        if ($result) {

            $teacher->user->update([
                'status' => 'active',
            ]);

            event(new TeacherRestored(
                $teacher,
                Auth::id()
            ));
        }

        return $result;
    }

    public function addQualification(
        int $teacherId,
        array $data
    ): object {

        $teacher = $this->teacherRepository
            ->findById($teacherId);

        $qualification = TeacherQualification::create([
            ...$data,
            'teacher_id' => $teacherId,
        ]);

        event(new QualificationAdded(
            $teacher,
            $qualification,
            Auth::id()
        ));

        return $qualification;
    }

    public function deleteQualification(
        int $qualificationId
    ): bool {

        $qualification = TeacherQualification::findOrFail(
            $qualificationId
        );

        $teacherId = $qualification->teacher_id;

        $result = $qualification->delete();

        if ($result) {

            event(new QualificationDeleted(
                $qualification,
                Auth::id()
            ));
        }

        return $result;
    }

    public function toggleStatus(int $id): Teacher
    {
        $teacher = $this->teacherRepository
            ->findById($id);

        $oldStatus = $teacher->status;

        $newStatus = $oldStatus === 'active'
            ? 'inactive'
            : 'active';

        $teacher = $this->teacherRepository
            ->update($id, [
                'status' => $newStatus,
            ]);

        event(new TeacherStatusToggled(
            $teacher,
            $oldStatus,
            $newStatus,
            Auth::id()
        ));

        return $teacher;
    }
}

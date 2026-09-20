<?php

namespace Modules\Academic\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Academic\Contracts\Repositories\TeacherRepositoryInterface;
use Modules\Academic\Contracts\Services\TeacherServiceInterface;
use Modules\Academic\Entities\TeacherQualification;
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

    public function createTeacher(array $data): object
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'first_name'     => $data['first_name'],
                'last_name'     => $data['last_name'],
                'first_name_ar' => $data['first_name_ar'],
                'last_name_ar'     => $data['last_name_ar'],
                'gender'     => $data['gender'],
                'date_of_birth'     => $data['date_of_birth'],
                'phone'     => $data['phone'],
                'avatar' => $data['avatar'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password'] ?? 'Teacher@123'),
                'user_type'     => 'teacher',
            ]);

            $data['user_id']     = $user->id;
            $data['employee_id'] = $this->teacherRepository->generateEmployeeId();

            $teacher = $this->teacherRepository->create($data);

            if (!empty($data['qualifications'])) {
                foreach ($data['qualifications'] as $q) {
                    TeacherQualification::create(array_merge($q, ['teacher_id' => $teacher->id]));
                }
            }

            $user->assignRole('teacher');

            return $teacher->load(['user', 'qualifications']);
        });
    }

    public function updateTeacher(int $id, array $data): object
    {
        return DB::transaction(function () use ($id, $data) {
            $teacher    = $this->teacherRepository->update($id, $data);
            $userUpdate = [];

            if (!empty($data['email'])) {
                $userUpdate['email'] = $data['email'];
                $userUpdate['name']  = ($data['first_name'] ?? $teacher->first_name)
                    . ' ' . ($data['last_name'] ?? $teacher->last_name);
            }
            if (!empty($data['password'])) {
                $userUpdate['password'] = Hash::make($data['password']);
            }
            if (!empty($userUpdate)) {
                $teacher->user->update($userUpdate);
            }

            return $teacher;
        });
    }

    public function deleteTeacher(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $teacher = $this->teacherRepository->findById($id);
            $teacher->user->update(['status' => 'inactive']);
            return $this->teacherRepository->delete($id);
        });
    }

    public function restoreTeacher(int $id): bool
    {
        return $this->teacherRepository->restore($id);
    }

    public function getTeacherWithQualifications(int $id)
    {
        return $this->teacherRepository->getWithQualifications($id);
    }

    public function addQualification(int $teacherId, array $data): object
    {
        $this->teacherRepository->findById($teacherId); // ensure exists
        return TeacherQualification::create(array_merge($data, ['teacher_id' => $teacherId]));
    }

    public function deleteQualification(int $qualificationId): bool
    {
        return TeacherQualification::findOrFail($qualificationId)->delete();
    }

    public function getTeacherTimetable(int $teacherId, int $semesterId)
    {
        return $this->teacherRepository->getTeacherTimetable($teacherId, $semesterId);
    }

    public function toggleStatus(int $id): object
    {
        $teacher   = $this->teacherRepository->findById($id);
        $newStatus = $teacher->status === 'active' ? 'inactive' : 'active';
        return $this->teacherRepository->update($id, ['status' => $newStatus]);
    }
}

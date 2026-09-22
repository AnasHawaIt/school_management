<?php

namespace App\Services;

use App\Contracts\Repositories\GuardianRepositoryInterface;
use App\Contracts\Services\GuardianServiceInterface;
use App\Entities\Guardian;
use App\Events\GuardianEvens\GuardianDeleted;
use App\Events\GuardianEvens\GuardianRestored;
use App\Events\GuardianEvens\GuardianUpdated;
use App\Events\GuardianEvens\StudentAttachedToGuardian;
use App\Events\GuardianEvens\StudentDetachedFromGuardian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Academic\app\Events\GuardianEvens\GuardianCreated;
use Modules\Core\app\Entities\User;


class GuardianService implements GuardianServiceInterface
{
    public function __construct(
        protected GuardianRepositoryInterface $guardianRepository,
    ) {}

    public function getAllGuardians(array $filters = [])
    {
        return $this->guardianRepository
            ->getAll($filters)
            ->through(
                fn ($guardian) => $guardian->load([
                    'students',
                    'user',
                ])
            );
    }

    public function getGuardian(int $id)
    {
        return $this->guardianRepository
            ->findById($id)
            ->load([
                'students',
                'user',
            ]);
    }

    public function createGuardian(array $data): Guardian
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'first_name'     => $data['first_name'],
                'last_name'      => $data['last_name'],
                'first_name_ar'  => $data['first_name_ar'],
                'last_name_ar'   => $data['last_name_ar'],
                'gender'         => $data['gender'],
                'email'          => $data['email'],
                'date_of_birth'  => $data['date_of_birth'],
                'phone'          => $data['phone'],
                'avatar'         => $data['avatar'] ?? null,
                'password'       => Hash::make(
                    $data['password'] ?? 'Parent@123'
                ),
                'user_type'      => 'parent',
            ]);

            /*
           |--------------------------------------------------------------------------
           | Create Guardian
           |--------------------------------------------------------------------------
           */

            $guardianData = $data;

            $guardianData['user_id'] = $user->id;

            $guardian = $this->guardianRepository->create(
                $guardianData
            );

            /*
            |--------------------------------------------------------------------------
            | Attach Students
            |--------------------------------------------------------------------------
            */

            if (!empty($data['students'])) {

                foreach ($data['students'] as $student) {

                    $this->guardianRepository->attachStudent(
                        $guardian->id,
                        $student['student_id'],
                        [
                            'relationship'       =>
                                $student['relationship'],

                            'is_primary_contact' =>
                                $student['is_primary_contact'] ?? false,

                            'can_pickup' =>
                                $student['can_pickup'] ?? true,
                        ]
                    );
                }
            }

            $user->assignRole('parent');

            /*
           |--------------------------------------------------------------------------
           | Domain Event
           |--------------------------------------------------------------------------
           */

            event(new GuardianCreated(
                $guardian->load(['user', 'students']),
                Auth::id()
            ));

            return $guardian->load([
                'user',
                'students',
            ]);
        });
    }

    public function updateGuardian(int $id, array $data): Guardian
    {
        return DB::transaction(function () use ($id, $data) {
            /*
            |--------------------------------------------------------------------------
            | Get Guardian
            |--------------------------------------------------------------------------
            */

            $guardian = $this->guardianRepository
                ->findById($id);

            /*
            |--------------------------------------------------------------------------
            | Update Guardian
            |--------------------------------------------------------------------------
            */

            $guardian = $this->guardianRepository
                ->update($id, $data);

            /*
            |--------------------------------------------------------------------------
            | Capture Guardian Changes
            |--------------------------------------------------------------------------
            */

            $guardianChanges = $guardian->getChanges();

            /*
            |--------------------------------------------------------------------------
            | Update User
            |--------------------------------------------------------------------------
            */

            $userUpdate = [];

            if (array_key_exists('email', $data)) {
                $userUpdate['email'] = $data['email'];
                if (array_key_exists('first_name', $data)) {
                    $userUpdate['first_name'] = $data['first_name'];
                }

                if (array_key_exists('last_name', $data)) {
                    $userUpdate['last_name'] = $data['last_name'];
                }

                if (array_key_exists('first_name_ar', $data)) {
                    $userUpdate['first_name_ar'] =
                        $data['first_name_ar'];
                }

                if (array_key_exists('last_name_ar', $data)) {
                    $userUpdate['last_name_ar'] =
                        $data['last_name_ar'];
                }

                if (array_key_exists('phone', $data)) {
                    $userUpdate['phone'] = $data['phone'];
                }

                if (array_key_exists('gender', $data)) {
                    $userUpdate['gender'] = $data['gender'];
                }

                if (array_key_exists('date_of_birth', $data)) {
                    $userUpdate['date_of_birth'] =
                        $data['date_of_birth'];
                }
            }
            if (!empty($data['password'])) {
                $userUpdate['password'] =
                    Hash::make($data['password']);
            }
            /*
              |--------------------------------------------------------------------------
              | Apply User Changes
              |--------------------------------------------------------------------------
              */

            $userChanges = [];

            if (!empty($userUpdate)) {

                $user = $guardian->user;

                $user->update($userUpdate);

                $userChanges = $user->getChanges();
            }

            /*
             |--------------------------------------------------------------------------
             | Domain Event
             |--------------------------------------------------------------------------
             */

            event(new GuardianUpdated(
                $guardian->fresh(['user', 'students']),
                [
                    'guardian' => $guardianChanges,
                    'user'     => $userChanges,
                ],
                Auth::id()
            ));

            return $guardian->fresh([
                'user',
                'students',
            ]);
        });
    }

    public function deleteGuardian(int $id): bool
    {
        return DB::transaction(function () use ($id) {

            /*
            |--------------------------------------------------------------------------
            | Get Guardian
            |--------------------------------------------------------------------------
            */

            $guardian = $this->guardianRepository
                ->findById($id);

            /*
            |--------------------------------------------------------------------------
            | Deactivate User
            |--------------------------------------------------------------------------
            */

            $guardian->user->update([
                'status' => 'inactive',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Delete Guardian
            |--------------------------------------------------------------------------
            */

            $result = $this->guardianRepository
                ->delete($id);

            /*
            |--------------------------------------------------------------------------
            | Domain Event
            |--------------------------------------------------------------------------
            */

            if ($result) {
                event(new GuardianDeleted(
                    $guardian,
                    Auth::id()
                ));
            }

            return $result;

        });
    }

    public function restoreGuardian(int $id): bool
    {
        return DB::transaction(function () use ($id) {

            /*
            |--------------------------------------------------------------------------
            | Get Guardian
            |--------------------------------------------------------------------------
            */

            $guardian = $this->guardianRepository
                ->findById($id);

            /*
            |--------------------------------------------------------------------------
            | Restore Guardian
            |--------------------------------------------------------------------------
            */

            $result = $this->guardianRepository
                ->restore($id);

            /*
            |--------------------------------------------------------------------------
            | Reactivate User
            |--------------------------------------------------------------------------
            */

            if ($result) {

                $guardian->user->update([
                    'status' => 'active',
                ]);

                /*
                |--------------------------------------------------------------------------
                | Domain Event
                |--------------------------------------------------------------------------
                */

                event(new GuardianRestored(
                    $guardian->fresh([
                        'user',
                        'students',
                    ]),
                    Auth::id()
                ));
            }

            return $result;
        });
    }

    /**
     * Attach student to guardian.
     */
    public function attachStudent(
        int $guardianId,
        int $studentId,
        array $pivotData
    ): bool {
        return DB::transaction(function () use (
            $guardianId,
            $studentId,
            $pivotData
        ) {

            $guardian = $this->guardianRepository
                ->findById($guardianId);

            $result = $this->guardianRepository
                ->attachStudent(
                    $guardianId,
                    $studentId,
                    $pivotData
                );

            if ($result) {

                event(new StudentAttachedToGuardian(
                    $guardian,
                    $studentId,
                    $pivotData,
                    Auth::id()
                ));
            }

            return $result;
        });}

    /**
     * Detach student from guardian.
     */
    public function detachStudent(
        int $guardianId,
        int $studentId
    ): bool {
        return DB::transaction(function () use (
            $guardianId,
            $studentId
        ) {

            $guardian = $this->guardianRepository
                ->findById($guardianId);

            $result = $this->guardianRepository
                ->detachStudent(
                    $guardianId,
                    $studentId
                );

            if ($result) {

                event(new StudentDetachedFromGuardian(
                    $guardian,
                    $studentId,
                    Auth::id()
                ));
            }

            return $result;
        });
    }

    public function getGuardianWithStudents(int $id)
    {
        return $this->guardianRepository
            ->getWithStudents($id);
    }
}

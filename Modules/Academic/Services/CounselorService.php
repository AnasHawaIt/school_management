<?php

namespace Modules\Academic\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;

use Modules\Academic\Entities\Counselor;
use Modules\Academic\Events\CounselorEvents\CounselorCreated;
use Modules\Academic\Events\CounselorEvents\CounselorDeleted;
use Modules\Academic\Events\CounselorEvents\CounselorRestored;
use Modules\Academic\Events\CounselorEvents\CounselorSectionAssigned;
use Modules\Academic\Events\CounselorEvents\CounselorSectionUnassigned;
use Modules\Academic\Events\CounselorEvents\CounselorStatusToggled;
use Modules\Academic\Events\CounselorEvents\CounselorUpdated;
use Modules\Core\Entities\User;

use Modules\Academic\Contracts\Services\CounselorServiceInterface;
use Modules\Academic\Contracts\Repositories\CounselorRepositoryInterface;

class CounselorService implements CounselorServiceInterface
{
    public function __construct(
        protected CounselorRepositoryInterface $counselorRepository,
    ) {}

    /**
     * Get all counselors.
     */
    public function getAll(array $filters = [])
    {
        return $this->counselorRepository->getAll($filters);
    }

    /**
     * Get counselor by ID.
     */
    public function getCounselor(int $id)
    {
        return $this->counselorRepository->findById($id);
    }

    /**
     * Create counselor.
     */
    public function createCounselor(array $data): Counselor
    {
        return DB::transaction(function () use ($data) {

            $avatarPath = null;

            if (
                isset($data['avatar']) &&
                $data['avatar'] instanceof UploadedFile
            ) {
                $fileName = time()
                    . '_'
                    . uniqid()
                    . '.'
                    . $data['avatar']->getClientOriginalExtension();

                $path = $data['avatar']->storeAs(
                    'avatars/counselors',
                    $fileName,
                    'public'
                );

                $avatarPath = '/' . $path;
            }

            /*
             * Create User
             */
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'gender'     => $data['gender'],
                'password'   => Hash::make(
                    $data['password'] ?? 'Counselor@123'
                ),
                'user_type'  => 'counselor',
                'is_active'  => true,
                'avatar'     => $avatarPath,
            ]);

            /*
             * Prepare Counselor data
             */
            $data['user_id'] = $user->id;

            $data['counselor_id'] =
                $this->counselorRepository->generateCounselorId();

            /*
             * Create Counselor
             */
            $counselor = $this->counselorRepository
                ->create($data)
                ->load('user');

            /*
             * Domain Event
             */
            event(new CounselorCreated(
                $counselor,
                Auth::id()
            ));

            return $counselor;
        });
    }

    /**
     * Update counselor.
     */
    public function updateCounselor(int $id, array $data): Counselor
    {
        return DB::transaction(function () use ($id, $data) {

            /*
             * Get current counselor
             */
            $counselor = $this->counselorRepository->findById($id);

            /*
             * Update Counselor
             */
            $counselor = $this->counselorRepository->update(
                $id,
                $data
            );

            /*
             * Update related User
             */
            $userUpdate = [];

            if (array_key_exists('first_name', $data)) {
                $userUpdate['first_name'] = $data['first_name'];
            }

            if (array_key_exists('last_name', $data)) {
                $userUpdate['last_name'] = $data['last_name'];
            }

            if (array_key_exists('email', $data)) {
                $userUpdate['email'] = $data['email'];
            }

            if (!empty($data['password'])) {
                $userUpdate['password'] = Hash::make(
                    $data['password']
                );
            }

            if (!empty($userUpdate)) {
                $counselor->user->update($userUpdate);
            }

            /*
             * Reload relationships
             */
            $counselor->load('user');

            /*
             * Detect changes
             */
            $changes = $counselor->getChanges();

            /*
             * Domain Event
             */
            event(new CounselorUpdated(
                $counselor,
                $changes,
                Auth::id()
            ));

            return $counselor;
        });
    }

    /**
     * Delete counselor.
     */
    public function deleteCounselor(int $id): bool
    {
        return DB::transaction(function () use ($id) {

            $counselor = $this->counselorRepository->findById($id);

            /*
             * Disable User account
             */
            $counselor->user->update([
                'is_active' => false,
            ]);

            /*
             * Delete counselor
             */
            $result = $this->counselorRepository->delete($id);

            /*
             * Domain Event
             */
            if ($result) {
                event(new CounselorDeleted(
                    $counselor,
                    Auth::id()
                ));
            }

            return $result;
        });
    }

    /**
     * Restore counselor.
     */
    public function restoreCounselor(int $id): bool
    {
        return DB::transaction(function () use ($id) {

            $counselor = $this->counselorRepository->findById($id);

            /*
             * Restore counselor
             */
            $result = $this->counselorRepository->restore($id);

            /*
             * Restore User account
             */
            if ($result) {
                $counselor->user->update([
                    'is_active' => true,
                ]);

                event(new CounselorRestored(
                    $counselor,
                    Auth::id()
                ));
            }

            return $result;
        });
    }

    /**
     * Assign counselor to section.
     */
    public function assignSection(
        int $counselorId,
        int $sectionId,
        int $academicYearId
    ): bool {
        return DB::transaction(function () use (
            $counselorId,
            $sectionId,
            $academicYearId
        ) {

            $result = $this->counselorRepository->assignSection(
                $counselorId,
                $sectionId,
                $academicYearId
            );

            if ($result) {

                $counselor = $this->counselorRepository
                    ->findById($counselorId);

                event(new CounselorSectionAssigned(
                    $counselor,
                    $sectionId,
                    $academicYearId,
                    Auth::id()
                ));
            }

            return $result;
        });
    }

    /**
     * Unassign counselor from section.
     */
    public function unassignSection(
        int $counselorId,
        int $sectionId,
        int $academicYearId
    ): bool {
        return DB::transaction(function () use (
            $counselorId,
            $sectionId,
            $academicYearId
        ) {

            $result = $this->counselorRepository->unassignSection(
                $counselorId,
                $sectionId,
                $academicYearId
            );

            if ($result) {

                $counselor = $this->counselorRepository
                    ->findById($counselorId);

                event(new CounselorSectionUnassigned(
                    $counselor,
                    $sectionId,
                    $academicYearId,
                    Auth::id()
                ));
            }

            return $result;
        });
    }

    /**
     * Get counselor sections.
     */
    public function getCounselorSections(
        int $counselorId,
        int $academicYearId
    ) {
        return $this->counselorRepository->getSections(
            $counselorId,
            $academicYearId
        );
    }

    /**
     * Toggle counselor status.
     */
    public function toggleStatus(int $id): Counselor
    {
        return DB::transaction(function () use ($id) {

            $counselor = $this->counselorRepository->findById($id);

            $oldStatus = $counselor->status;

            $newStatus = $oldStatus === 'active'
                ? 'inactive'
                : 'active';

            $counselor = $this->counselorRepository->update(
                $id,
                [
                    'status' => $newStatus,
                ]
            );

            /*
             * Domain Event
             */
            event(new CounselorStatusToggled(
                $counselor,
                $oldStatus,
                $newStatus,
                Auth::id()
            ));

            return $counselor;
        });
    }
}

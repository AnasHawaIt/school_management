<?php

namespace Modules\Academic\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Academic\Contracts\Repositories\CounselorRepositoryInterface;
use Modules\Academic\Contracts\Services\CounselorServiceInterface;
use Modules\Core\app\Entities\User;

class CounselorService implements CounselorServiceInterface
{
    public function __construct(
        protected CounselorRepositoryInterface $counselorRepository,
    ) {}

    public function getAll(array $filters = [])
    {
        return $this->counselorRepository->getAll($filters);
    }

    public function getCounselor(int $id)
    {
        return $this->counselorRepository->findById($id);
    }

    public function createCounselor(array $data): object
    {

        return DB::transaction(function () use ($data) {

            $avatarPath = null;

            if (isset($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {

                $fileName = time() . '_' . uniqid() . '.' . $data['avatar']->getClientOriginalExtension();

                $path = $data['avatar']->storeAs('avatars/counselors', $fileName, 'public');

                $avatarPath = '/' . $path;

            }
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'gender'     => $data['gender'],
                'password'   => Hash::make($data['password'] ?? 'Counselor@123'),
                'user_type'  => 'counselor',
                'is_active'  => true,
                'avatar'     => $avatarPath,
            ]);

            $data['user_id']      = $user->id;
            $data['counselor_id'] = $this->counselorRepository->generateCounselorId();

            return $this->counselorRepository->create($data)->load('user');
        });
    }

    public function updateCounselor(int $id, array $data): object
    {

        return DB::transaction(function () use ($id, $data) {
            $counselor  = $this->counselorRepository->update($id, $data);
            $userUpdate = [];

            if (!empty($data['first_name'])) $userUpdate['first_name'] = $data['first_name'];
            if (!empty($data['last_name']))  $userUpdate['last_name']  = $data['last_name'];
            if (!empty($data['email']))      $userUpdate['email']      = $data['email'];
            if (!empty($data['password']))   $userUpdate['password']   = Hash::make($data['password']);

            if (!empty($userUpdate)) $counselor->user->update($userUpdate);

            return $counselor;
        });
    }

    public function deleteCounselor(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $counselor = $this->counselorRepository->findById($id);
            $counselor->user->update(['is_active' => false]);
            return $this->counselorRepository->delete($id);
        });
    }

    public function restoreCounselor(int $id): bool
    {
        return $this->counselorRepository->restore($id);
    }

    public function assignSection(int $counselorId, int $sectionId, int $academicYearId): bool
    {
        return $this->counselorRepository->assignSection($counselorId, $sectionId, $academicYearId);
    }

    public function unassignSection(int $counselorId, int $sectionId, int $academicYearId): bool
    {
        return $this->counselorRepository->unassignSection($counselorId, $sectionId, $academicYearId);
    }

    public function getCounselorSections(int $counselorId, int $academicYearId)
    {
        return $this->counselorRepository->getSections($counselorId, $academicYearId);
    }

    public function toggleStatus(int $id): object
    {
        $counselor = $this->counselorRepository->findById($id);
        $newStatus = $counselor->status === 'active' ? 'inactive' : 'active';
        return $this->counselorRepository->update($id, ['status' => $newStatus]);
    }
}

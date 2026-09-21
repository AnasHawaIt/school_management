<?php

namespace App\Contracts\Repositories;

use App\Entities\InspectionProgram;
use Illuminate\Pagination\LengthAwarePaginator;

interface InspectionProgramRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;
    public function create(array $data): InspectionProgram;
    public function update(int $id, array $data): InspectionProgram;
    public function delete(int $id): bool;
    public function restore(int $id): bool;
    public function assignCounselor(int $programId, int $counselorId, string $role): bool;
    public function unassignCounselor(int $programId, int $counselorId): bool;
    public function updateCounselorObservation(int $programId, int $counselorId, array $data): bool;
    public function updateStatus(int $id, string $status): InspectionProgram;
    public function getBySection(int $sectionId, array $filters = []);
    public function getByCounselor(int $counselorId, array $filters = []);
    public function getCurrentCounselorProgram(int $counselorId);
    public function setCurrent(int $id): bool;}

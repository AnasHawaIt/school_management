<?php

namespace Modules\Examination\Contracts\Repositories;

interface ExamRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function create(array $data): object;
    public function update(int $id, array $data): object;
    public function delete(int $id): bool;
    public function restore(int $id): bool;
    public function getBySection(int $sectionId, int $semesterId);
    public function getByTeacher(int $teacherId, int $semesterId);
    public function updateStatus(int $id, string $status): object;
}

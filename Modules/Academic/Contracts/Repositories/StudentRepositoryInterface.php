<?php

namespace Modules\Academic\Contracts\Repositories;

interface StudentRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function findByStudentId(string $studentId);
    public function create(array $data): object;
    public function update(int $id, array $data): object;
    public function delete(int $id): bool;
    public function restore(int $id): bool;
    public function transferSection(int $studentId, int $newSectionId): bool;
    public function promoteStudents(int $fromSectionId, int $toSectionId): int;
    public function getWithParents(int $id);
    public function getWithMedicalRecord(int $id);
    public function generateStudentId(): string;
    public function getBySection(int $sectionId);
    public function getStatsBySection(int $sectionId): array;
}

<?php

namespace Modules\Academic\Contracts\Repositories;

interface StudentPointRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function create(array $data): object;
    public function bulkCreate(array $records): bool;
    public function delete(int $id): bool;


    public function getStudentTotal(int $studentId, int $semesterId): array;


    public function getStudentHistory(int $studentId, array $filters = []);


    public function getSectionRanking(int $sectionId, int $semesterId);


    public function getStats(array $filters = []): array;
}

<?php

namespace Modules\Examination\Contracts\Repositories;

interface ReportCardRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function findByStudentAndSemester(int $studentId, int $semesterId);
    public function create(array $data): object;
    public function update(int $id, array $data): object;
    public function generateForSection(int $sectionId, int $semesterId): int;
    public function publish(int $id): object;
    public function publishAll(int $sectionId, int $semesterId): int;
    public function getBySection(int $sectionId, int $semesterId);
}

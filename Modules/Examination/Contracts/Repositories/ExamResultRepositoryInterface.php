<?php

namespace Modules\Examination\Contracts\Repositories;

interface ExamResultRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function create(array $data): object;
    public function update(int $id, array $data): object;
    public function bulkCreate(int $examId, array $results): bool;
    public function getByExam(int $examId);
    public function getByStudent(int $studentId, array $filters = []);
    public function getExamStats(int $examId): array;
    public function getStudentStats(int $studentId, int $semesterId): array;
}

<?php

namespace Modules\Examination\Contracts\Services;

interface ExamResultServiceInterface
{
    public function getAll(array $filters = []);
    public function getResult(int $id);
    public function enterResult(array $data): object;
    public function bulkEnter(int $examId, array $results): bool;
    public function updateResult(int $id, array $data): object;
    public function getExamResults(int $examId);
    public function getExamStats(int $examId): array;
    public function getStudentResults(int $studentId, array $filters = []);
    public function getStudentStats(int $studentId, int $semesterId): array;
}

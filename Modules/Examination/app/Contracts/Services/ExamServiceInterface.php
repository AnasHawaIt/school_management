<?php

namespace Modules\Examination\Contracts\Services;

interface ExamServiceInterface
{
    public function getAll(array $filters = []);
    public function getExam(int $id);
    public function createExam(array $data): object;
    public function updateExam(int $id, array $data): object;
    public function deleteExam(int $id): bool;
    public function restoreExam(int $id): bool;
    public function updateStatus(int $id, string $status): object;
    public function getSectionExams(int $sectionId, int $semesterId);
    public function getTeacherExams(int $teacherId, int $semesterId);
}

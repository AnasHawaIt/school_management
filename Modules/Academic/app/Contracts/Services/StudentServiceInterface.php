<?php

namespace App\Contracts\Services;

use App\Entities\Student;

interface StudentServiceInterface
{
    public function getAllStudents(array $filters = []);
    public function getStudent(int $id);
    public function createStudent(array $data): Student;
    public function updateStudent(int $id, array $data): Student;
    public function deleteStudent(int $id): bool;
    public function restoreStudent(int $id): bool;
    public function transferSection(int $studentId, int $newSectionId): bool;
    public function promoteStudents(int $fromSectionId, int $toSectionId): array;
    public function getStudentWithParents(int $id);
    public function getStudentWithMedicalRecord(int $id);
    public function updateMedicalRecord(int $studentId, array $data): object;
    public function getStudentsBySection(int $sectionId);
    public function getSectionStats(int $sectionId): array;
    public function toggleStatus(int $id, string $status): Student;
    public function assignStudentToSection(int $sectionId, int $studentId, int $semesterId, int $academicYearId): bool;
}

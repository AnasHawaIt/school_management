<?php

namespace Modules\Examination\Services;

use Modules\Examination\Contracts\Services\ExamServiceInterface;
use Modules\Examination\Contracts\Repositories\ExamRepositoryInterface;

class ExamService implements ExamServiceInterface
{
    public function __construct(protected ExamRepositoryInterface $repository) {}

    public function getAll(array $filters = [])
    {
        return $this->repository->getAll($filters);
    }

    public function getExam(int $id)
    {
        return $this->repository->findById($id);
    }

    public function createExam(array $data): object
    {
        return $this->repository->create($data);
    }

    public function updateExam(int $id, array $data): object
    {
        $exam = $this->repository->findById($id);

        if ($exam->status === 'completed') {
            throw new \Exception('Cannot edit a completed exam.');
        }

        return $this->repository->update($id, $data);
    }

    public function deleteExam(int $id): bool
    {
        $exam = $this->repository->findById($id);

        if ($exam->status === 'completed') {
            throw new \Exception('Cannot delete a completed exam.');
        }

        return $this->repository->delete($id);
    }

    public function restoreExam(int $id): bool
    {
        return $this->repository->restore($id);
    }

    public function updateStatus(int $id, string $status): object
    {
        return $this->repository->updateStatus($id, $status);
    }

    public function getSectionExams(int $sectionId, int $semesterId)
    {
        return $this->repository->getBySection($sectionId, $semesterId);
    }

    public function getTeacherExams(int $teacherId, int $semesterId)
    {
        return $this->repository->getByTeacher($teacherId, $semesterId);
    }
}

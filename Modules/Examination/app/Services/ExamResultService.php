<?php

namespace Modules\Examination\Services;

use Modules\Examination\Contracts\Services\ExamResultServiceInterface;
use Modules\Examination\Contracts\Repositories\ExamResultRepositoryInterface;
use Modules\Examination\Contracts\Repositories\ExamRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ExamResultService implements ExamResultServiceInterface
{
    public function __construct(
        protected ExamResultRepositoryInterface $resultRepository,
        protected ExamRepositoryInterface       $examRepository,
    ) {}

    public function getAll(array $filters = [])
    {
        return $this->resultRepository->getAll($filters);
    }

    public function getResult(int $id)
    {
        return $this->resultRepository->findById($id);
    }

    public function enterResult(array $data): object
    {
        $data['entered_by'] = Auth::id();
        return $this->resultRepository->create($data);
    }

    public function bulkEnter(int $examId, array $results): bool
    {
        // تحقق أن الامتحان موجود
        $this->examRepository->findById($examId);

        $enteredBy = Auth::id();
        $prepared  = collect($results)->map(fn($r) => array_merge($r, [
            'entered_by' => $enteredBy,
        ]))->toArray();

        $success = $this->resultRepository->bulkCreate($examId, $prepared);

        // غيّر حالة الامتحان لـ completed تلقائياً
        $this->examRepository->updateStatus($examId, 'completed');

        return $success;
    }

    public function updateResult(int $id, array $data): object
    {
        return $this->resultRepository->update($id, $data);
    }

    public function getExamResults(int $examId)
    {
        return $this->resultRepository->getByExam($examId);
    }

    public function getExamStats(int $examId): array
    {
        return $this->resultRepository->getExamStats($examId);
    }

    public function getStudentResults(int $studentId, array $filters = [])
    {
        return $this->resultRepository->getByStudent($studentId, $filters);
    }

    public function getStudentStats(int $studentId, int $semesterId): array
    {
        return $this->resultRepository->getStudentStats($studentId, $semesterId);
    }
}

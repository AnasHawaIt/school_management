<?php

namespace Modules\Examination\Services;

use Illuminate\Support\Facades\DB;
use Modules\Examination\Contracts\Services\ExamServiceInterface;
use Modules\Examination\Contracts\Repositories\ExamRepositoryInterface;
use Modules\Examination\Events\ExamCreated;
use Modules\Examination\Events\ExamDeleted;
use Modules\Examination\Events\ExamRestored;
use Modules\Examination\Events\ExamStatusUpdated;
use Modules\Examination\Events\ExamUpdated;

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
        $exam = DB::transaction(function () use ($data) {
            return $this->repository->create($data);
        });

        ExamCreated::dispatch($exam);

        return $exam;
    }

    public function updateExam(int $id, array $data): object
    {
        $exam = $this->repository->findById($id);

        if ($exam->status === 'completed') {
            throw new \Exception(
                'Cannot edit a completed exam.'
            );
        }

        $exam = DB::transaction(function () use ($id, $data) {
            return $this->repository->update($id, $data);
        });

        ExamUpdated::dispatch($exam);

        return $exam;
    }

    public function deleteExam(int $id): bool
    {
        $exam = $this->repository->findById($id);

        if ($exam->status === 'completed') {
            throw new \Exception(
                'Cannot delete a completed exam.'
            );
        }

        $deleted = DB::transaction(function () use ($id) {
            return $this->repository->delete($id);
        });

        if ($deleted) {
            ExamDeleted::dispatch($exam);
        }

        return $deleted;
    }

    public function restoreExam(int $id): bool
    {
        $exam = $this->repository
            ->findTrashedById($id);

        $restored = DB::transaction(function () use ($id) {
            return $this->repository->restore($id);
        });

        if ($restored) {
            $exam->restore();

            ExamRestored::dispatch($exam);
        }

        return $restored;
    }


    public function updateStatus(
        int $id,
        string $status
    ): object {

        $exam = $this->repository->findById($id);

        $oldStatus = $exam->status;

        $exam = DB::transaction(function () use (
            $id,
            $status
        ) {
            return $this->repository->updateStatus(
                $id,
                $status
            );
        });

        if ($oldStatus !== $status) {
            ExamStatusUpdated::dispatch(
                $exam,
                $oldStatus,
                $status
            );
        }

        return $exam;
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

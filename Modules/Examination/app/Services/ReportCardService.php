<?php

namespace Modules\Examination\Services;

use Modules\Examination\Contracts\Services\ReportCardServiceInterface;
use Modules\Examination\Contracts\Repositories\ReportCardRepositoryInterface;

class ReportCardService implements ReportCardServiceInterface
{
    public function __construct(
        protected ReportCardRepositoryInterface $repository,
    ) {}

    public function getAll(array $filters = [])
    {
        return $this->repository->getAll($filters);
    }

    public function getReportCard(int $id)
    {
        return $this->repository->findById($id);
    }

    public function getStudentReportCard(int $studentId, int $semesterId)
    {
        $card = $this->repository->findByStudentAndSemester($studentId, $semesterId);

        if (!$card) {
            throw new \Exception('Report card not found for this student and semester.');
        }

        return $card;
    }

    public function generateForSection(int $sectionId, int $semesterId): array
    {
        $count = $this->repository->generateForSection($sectionId, $semesterId);
        return [
            'generated' => $count,
            'section_id' => $sectionId,
            'semester_id' => $semesterId,
        ];
    }

    public function publish(int $id): object
    {
        return $this->repository->publish($id);
    }

    public function publishAll(int $sectionId, int $semesterId): array
    {
        $count = $this->repository->publishAll($sectionId, $semesterId);
        return [
            'published'  => $count,
            'section_id' => $sectionId,
            'semester_id' => $semesterId,
        ];
    }

    public function getSectionReportCards(int $sectionId, int $semesterId)
    {
        return $this->repository->getBySection($sectionId, $semesterId);
    }
}

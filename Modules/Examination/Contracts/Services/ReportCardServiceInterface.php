<?php

namespace Modules\Examination\Contracts\Services;

interface ReportCardServiceInterface
{
    public function getAll(array $filters = []);
    public function getReportCard(int $id);
    public function getStudentReportCard(int $studentId, int $semesterId);
    public function generateForSection(int $sectionId, int $semesterId): array;
    public function publish(int $id): object;
    public function publishAll(int $sectionId, int $semesterId): array;
    public function getSectionReportCards(int $sectionId, int $semesterId);
}

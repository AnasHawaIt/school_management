<?php

namespace Modules\Examination\Repositories;

use Modules\Examination\Contracts\Repositories\ReportCardRepositoryInterface;
use Modules\Examination\Entities\ReportCard;
use Modules\Examination\Entities\ExamResult;
use Modules\Academic\Entities\Student;

class ReportCardRepository implements ReportCardRepositoryInterface
{
    public function __construct(protected ReportCard $model) {}

    public function getAll(array $filters = [])
    {
        $query = $this->model->with(['student.user', 'section', 'semester']);

        if (!empty($filters['section_id']))  $query->where('section_id', $filters['section_id']);
        if (!empty($filters['semester_id'])) $query->where('semester_id', $filters['semester_id']);
        if (!empty($filters['student_id']))  $query->where('student_id', $filters['student_id']);
        if (isset($filters['is_published'])) $query->where('is_published', $filters['is_published']);

        return $query->paginate($filters['per_page'] ?? 20);
    }

    public function findById(int $id)
    {
        return $this->model->with(['student.user', 'section.class.grade', 'semester', 'academicYear'])
            ->findOrFail($id);
    }

    public function findByStudentAndSemester(int $studentId, int $semesterId)
    {
        return $this->model->with(['student.user', 'semester'])
            ->where('student_id', $studentId)
            ->where('semester_id', $semesterId)
            ->first();
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): object
    {
        $card = $this->model->findOrFail($id);
        $card->update($data);
        return $card->fresh(['student.user', 'semester']);
    }

    public function generateForSection(int $sectionId, int $semesterId): int
    {
        $students = Student::where('current_section_id', $sectionId)
            ->where('status', 'active')
            ->get();

        $count = 0;

        foreach ($students as $student) {
            // احسب مجموع درجات الطالب من نتائج الامتحانات
            $results = ExamResult::with('exam')
                ->where('student_id', $student->id)
                ->where('is_absent', false)
                ->whereHas('exam', fn($q) => $q
                    ->where('section_id', $sectionId)
                    ->where('semester_id', $semesterId)
                    ->where('status', 'completed')
                )
                ->get();

            $totalMarks   = $results->sum(fn($r) => $r->exam->total_marks);
            $obtainedMarks = $results->sum('marks_obtained');
            $percentage   = $totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 2) : 0;

            $this->model->updateOrCreate(
                ['student_id' => $student->id, 'semester_id' => $semesterId],
                [
                    'section_id'       => $sectionId,
                    'academic_year_id' => $student->academic_year_id,
                    'total_marks'      => $totalMarks,
                    'obtained_marks'   => $obtainedMarks,
                    'percentage'       => $percentage,
                    'grade'            => $this->calculateGrade($percentage),
                    'result'           => $percentage >= 50 ? 'pass' : 'fail',
                ]
            );
            $count++;
        }

        // احسب الترتيب
        $this->calculateRanks($sectionId, $semesterId);

        return $count;
    }

    public function publish(int $id): object
    {
        $card = $this->model->findOrFail($id);
        $card->update(['is_published' => true]);
        return $card->fresh('student.user');
    }

    public function publishAll(int $sectionId, int $semesterId): int
    {
        return $this->model
            ->where('section_id', $sectionId)
            ->where('semester_id', $semesterId)
            ->update(['is_published' => true]);
    }

    public function getBySection(int $sectionId, int $semesterId)
    {
        return $this->model->with(['student.user'])
            ->where('section_id', $sectionId)
            ->where('semester_id', $semesterId)
            ->orderBy('rank')
            ->get();
    }

    // ─── Helpers ────────────────────────────────

    private function calculateGrade(float $percentage): string
    {
        return match(true) {
            $percentage >= 90 => 'A+',
            $percentage >= 80 => 'A',
            $percentage >= 70 => 'B',
            $percentage >= 60 => 'C',
            $percentage >= 50 => 'D',
            default           => 'F',
        };
    }

    private function calculateRanks(int $sectionId, int $semesterId): void
    {
        $cards = $this->model
            ->where('section_id', $sectionId)
            ->where('semester_id', $semesterId)
            ->orderBy('percentage', 'desc')
            ->get();

        foreach ($cards as $rank => $card) {
            $card->update(['rank' => $rank + 1]);
        }
    }
}

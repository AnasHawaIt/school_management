<?php

namespace Modules\Examination\Repositories;

use Modules\Examination\Contracts\Repositories\ExamResultRepositoryInterface;
use Modules\Examination\Entities\ExamResult;

class ExamResultRepository implements ExamResultRepositoryInterface
{
    public function __construct(protected ExamResult $model) {}

    public function getAll(array $filters = [])
    {
        $query = $this->model->with(['exam.subject', 'student.user', 'enteredBy']);

        if (!empty($filters['exam_id']))    $query->where('exam_id', $filters['exam_id']);
        if (!empty($filters['student_id'])) $query->where('student_id', $filters['student_id']);
        if (isset($filters['is_absent']))   $query->where('is_absent', $filters['is_absent']);

        return $query->paginate($filters['per_page'] ?? 20);
    }

    public function findById(int $id)
    {
        return $this->model->with(['exam.subject', 'student.user'])->findOrFail($id);
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): object
    {
        $result = $this->model->findOrFail($id);
        $result->update($data);
        return $result->fresh(['exam', 'student.user']);
    }

    public function bulkCreate(int $examId, array $results): bool
    {
        // حذف نتائج الامتحان ثم إعادة الإدراج
        $this->model->where('exam_id', $examId)->delete();

        $timestamp = now()->toDateTimeString();
        $prepared  = collect($results)->map(fn($r) => array_merge($r, [
            'exam_id'    => $examId,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]))->toArray();

        $this->model->insert($prepared);
        return true;
    }

    public function getByExam(int $examId)
    {
        return $this->model->with(['student.user'])
            ->where('exam_id', $examId)
            ->orderBy('student_id')
            ->get();
    }

    public function getByStudent(int $studentId, array $filters = [])
    {
        $query = $this->model->with(['exam.subject', 'exam.examType'])
            ->where('student_id', $studentId);

        if (!empty($filters['semester_id']))
            $query->whereHas('exam', fn($q) => $q->where('semester_id', $filters['semester_id']));

        return $query->paginate($filters['per_page'] ?? 20);
    }

    public function getExamStats(int $examId): array
    {
        $results = $this->model->where('exam_id', $examId)->where('is_absent', false);

        $total   = $this->model->where('exam_id', $examId)->count();
        $absent  = $this->model->where('exam_id', $examId)->where('is_absent', true)->count();
        $present = $total - $absent;

        $exam    = \Modules\Examination\Entities\Exam::findOrFail($examId);
        $passed  = (clone $results)->where('marks_obtained', '>=', $exam->pass_marks)->count();
        $failed  = $present - $passed;

        $avg = (clone $results)->avg('marks_obtained');
        $max = (clone $results)->max('marks_obtained');
        $min = (clone $results)->min('marks_obtained');

        return [
            'total'        => $total,
            'present'      => $present,
            'absent'       => $absent,
            'passed'       => $passed,
            'failed'       => $failed,
            'pass_rate'    => $present > 0 ? round(($passed / $present) * 100, 2) : 0,
            'average'      => round($avg ?? 0, 2),
            'highest'      => $max ?? 0,
            'lowest'       => $min ?? 0,
        ];
    }

    public function getStudentStats(int $studentId, int $semesterId): array
    {
        $results = $this->model->with('exam')
            ->where('student_id', $studentId)
            ->whereHas('exam', fn($q) => $q->where('semester_id', $semesterId))
            ->where('is_absent', false)
            ->get();

        $total     = $results->count();
        $passed    = $results->filter(fn($r) => $r->marks_obtained >= $r->exam->pass_marks)->count();
        $totalMark = $results->sum(fn($r) => $r->exam->total_marks);
        $obtained  = $results->sum('marks_obtained');

        return [
            'exams_taken'     => $total,
            'passed'          => $passed,
            'failed'          => $total - $passed,
            'total_marks'     => $totalMark,
            'obtained_marks'  => $obtained,
            'percentage'      => $totalMark > 0 ? round(($obtained / $totalMark) * 100, 2) : 0,
        ];
    }
}

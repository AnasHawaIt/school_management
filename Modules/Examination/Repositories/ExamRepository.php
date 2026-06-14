<?php

namespace Modules\Examination\Repositories;

use Modules\Examination\Contracts\Repositories\ExamRepositoryInterface;
use Modules\Examination\Entities\Exam;

class ExamRepository implements ExamRepositoryInterface
{
    public function __construct(protected Exam $model) {}

    public function getAll(array $filters = [])
    {
        $query = $this->model->with(['examType', 'subject', 'section', 'teacher.user', 'semester']);

        if (!empty($filters['section_id']))       $query->where('section_id', $filters['section_id']);
        if (!empty($filters['subject_id']))       $query->where('subject_id', $filters['subject_id']);
        if (!empty($filters['teacher_id']))       $query->where('teacher_id', $filters['teacher_id']);
        if (!empty($filters['semester_id']))      $query->where('semester_id', $filters['semester_id']);
        if (!empty($filters['academic_year_id'])) $query->where('academic_year_id', $filters['academic_year_id']);
        if (!empty($filters['exam_type_id']))     $query->where('exam_type_id', $filters['exam_type_id']);
        if (!empty($filters['status']))           $query->where('status', $filters['status']);
        if (!empty($filters['from_date']))        $query->where('exam_date', '>=', $filters['from_date']);
        if (!empty($filters['to_date']))          $query->where('exam_date', '<=', $filters['to_date']);

        return $query->orderBy('exam_date', 'desc')->paginate($filters['per_page'] ?? 15);
    }

    public function findById(int $id)
    {
        return $this->model->with([
            'examType', 'subject', 'section.class.grade',
            'teacher.user', 'semester', 'academicYear',
        ])->findOrFail($id);
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): object
    {
        $exam = $this->model->findOrFail($id);
        $exam->update($data);
        return $exam->fresh(['examType', 'subject', 'section', 'teacher.user']);
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function restore(int $id): bool
    {
        return $this->model->withTrashed()->findOrFail($id)->restore();
    }

    public function getBySection(int $sectionId, int $semesterId)
    {
        return $this->model->with(['examType', 'subject', 'teacher.user'])
            ->where('section_id', $sectionId)
            ->where('semester_id', $semesterId)
            ->orderBy('exam_date')
            ->get();
    }

    public function getByTeacher(int $teacherId, int $semesterId)
    {
        return $this->model->with(['examType', 'subject', 'section.class.grade'])
            ->where('teacher_id', $teacherId)
            ->where('semester_id', $semesterId)
            ->orderBy('exam_date')
            ->get();
    }

    public function updateStatus(int $id, string $status): object
    {
        $exam = $this->model->findOrFail($id);
        $exam->update(['status' => $status]);
        return $exam->fresh();
    }
}

<?php

namespace Modules\Academic\Repositories;

use Modules\Academic\Contracts\Repositories\TimetableRepositoryInterface;
use Modules\Academic\Entities\Timetable;

class TimetableRepository implements TimetableRepositoryInterface
{
    public function __construct(protected Timetable $model) {}

    public function getAll(array $filters = [])
    {
        $query = $this->model->with(['section.class.grade', 'subject', 'teacher', 'semester']);

        if (!empty($filters['section_id']))  $query->where('section_id', $filters['section_id']);
        if (!empty($filters['teacher_id']))  $query->where('teacher_id', $filters['teacher_id']);
        if (!empty($filters['semester_id'])) $query->where('semester_id', $filters['semester_id']);
        if (!empty($filters['day_of_week'])) $query->where('day_of_week', $filters['day_of_week']);

        return $query->orderBy('day_of_week')->orderBy('period_number')->paginate($filters['per_page'] ?? 50);
    }

    public function findById(int $id)
    {
        return $this->model->with(['section', 'subject', 'teacher', 'semester'])->findOrFail($id);
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): object
    {
        $timetable = $this->model->findOrFail($id);
        $timetable->update($data);
        return $timetable->fresh(['section', 'subject', 'teacher', 'semester']);
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function getBySectionAndSemester(int $sectionId, int $semesterId)
    {
        return $this->model->with(['subject', 'teacher'])
            ->where('section_id', $sectionId)
            ->where('semester_id', $semesterId)
            ->where('status', 'active')
            ->orderBy('day_of_week')
            ->orderBy('period_number')
            ->get()
            ->groupBy('day_of_week');
    }

    public function getByTeacherAndSemester(int $teacherId, int $semesterId)
    {
        return $this->model->with(['subject', 'section.class.grade'])
            ->where('teacher_id', $teacherId)
            ->where('semester_id', $semesterId)
            ->where('status', 'active')
            ->orderBy('day_of_week')
            ->orderBy('period_number')
            ->get()
            ->groupBy('day_of_week');
    }

    public function checkConflict(array $data, ?int $excludeId = null): bool
    {
        $base = fn() => $this->model
            ->where('semester_id', $data['semester_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('period_number', $data['period_number'])
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId));

        // تعارض الشعبة
        if ((clone $base())->where('section_id', $data['section_id'])->exists()) return true;

        // تعارض المعلم
        if ((clone $base())->where('teacher_id', $data['teacher_id'])->exists()) return true;

        return false;
    }
}

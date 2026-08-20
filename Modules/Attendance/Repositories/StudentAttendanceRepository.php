<?php

namespace Modules\Attendance\Repositories;

use Modules\Attendance\Contracts\Repositories\StudentAttendanceRepositoryInterface;
use Modules\Attendance\Entities\StudentAttendance;

class StudentAttendanceRepository implements StudentAttendanceRepositoryInterface
{
    public function __construct(protected StudentAttendance $model) {}

    public function getAll(array $filters = [])
    {
        $query = $this->model->with([
            'student.user',
            'section',
            'status',
            'recorder',
        ]);

        if (!empty($filters['section_id']))       $query->where('section_id', $filters['section_id']);
        if (!empty($filters['student_id']))       $query->where('student_id', $filters['student_id']);
        if (!empty($filters['semester_id']))      $query->where('semester_id', $filters['semester_id']);
        if (!empty($filters['academic_year_id'])) $query->where('academic_year_id', $filters['academic_year_id']);
        if (!empty($filters['status_id']))        $query->where('status_id', $filters['status_id']);
        if (!empty($filters['date']))             $query->where('date', $filters['date']);
        if (!empty($filters['from_date']))        $query->where('date', '>=', $filters['from_date']);
        if (!empty($filters['to_date']))          $query->where('date', '<=', $filters['to_date']);

        return $query->orderBy('date', 'desc')->paginate($filters['per_page'] ?? 20);
    }

    public function findById(int $id)
    {
        return $this->model->with(['student.user', 'section', 'status', 'recorder'])->findOrFail($id);
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): object
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);
        return $record->fresh(['student.user', 'status']);
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function bulkCreate(array $records): bool
    {
        if (empty($records)) return false;


        $this->model
            ->where('section_id', $records[0]['section_id'])
            ->where('date', $records[0]['date'])
            ->delete();

        $this->model->insert($records);
        return true;
    }

    public function getBySection(int $sectionId, string $date): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->with(['student.user', 'status'])
            ->where('section_id', $sectionId)
            ->where('date', $date)
            ->orderBy('student_id')
            ->get();
    }

    public function getByStudent(int $studentId, array $filters = [])
    {
        $query = $this->model->with(['status', 'section'])
            ->where('student_id', $studentId);

        if (!empty($filters['semester_id'])) $query->where('semester_id', $filters['semester_id']);
        if (!empty($filters['from_date']))   $query->where('date', '>=', $filters['from_date']);
        if (!empty($filters['to_date']))     $query->where('date', '<=', $filters['to_date']);

        return $query->orderBy('date', 'desc')->paginate($filters['per_page'] ?? 30);
    }

    public function getStudentStats(int $studentId, int $semesterId): array
    {
        $base = fn() => $this->model
            ->where('student_id', $studentId)
            ->where('semester_id', $semesterId);

        $total   = (clone $base())->count();
        $present = (clone $base())->whereHas('status', fn($q) => $q->where('is_present', true))->count();
        $absent  = (clone $base())->whereHas('status', fn($q) => $q->where('code', 'A'))->count();
        $late    = (clone $base())->whereHas('status', fn($q) => $q->where('code', 'L'))->count();
        $excused = (clone $base())->whereHas('status', fn($q) => $q->where('code', 'E'))->count();

        return [
            'total'           => $total,
            'present'         => $present,
            'absent'          => $absent,
            'late'            => $late,
            'excused'         => $excused,
            'attendance_rate' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
        ];
    }

    public function getSectionStats(int $sectionId, int $semesterId): array
    {
        $base = fn() => $this->model
            ->where('section_id', $sectionId)
            ->where('semester_id', $semesterId);

        $total   = (clone $base())->count();
        $present = (clone $base())->whereHas('status', fn($q) => $q->where('is_present', true))->count();
        $absent  = (clone $base())->whereHas('status', fn($q) => $q->where('code', 'A'))->count();

        return [
            'total_records'   => $total,
            'present'         => $present,
            'absent'          => $absent,
            'attendance_rate' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
        ];
    }

    public function findByStudentAndDate(int $studentId, string $date)
    {
        return $this->model
            ->where('student_id', $studentId)
            ->where('date', $date)
            ->first();
    }
}

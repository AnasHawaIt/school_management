<?php

namespace Modules\Attendance\app\Repositories;

use Modules\Attendance\app\Contracts\Repositories\TeacherAttendanceRepositoryInterface;
use Modules\Attendance\app\Entities\TeacherAttendance;

class TeacherAttendanceRepository implements TeacherAttendanceRepositoryInterface
{
    public function __construct(protected TeacherAttendance $model) {}

    public function getAll(array $filters = [])
    {
        $query = $this->model->with([
            'teacher.user', // الاسم عبر user
            'status',
            'recorder',
        ]);

        if (!empty($filters['teacher_id'])) $query->where('teacher_id', $filters['teacher_id']);
        if (!empty($filters['status_id']))  $query->where('status_id', $filters['status_id']);
        if (!empty($filters['date']))       $query->where('date', $filters['date']);
        if (!empty($filters['from_date']))  $query->where('date', '>=', $filters['from_date']);
        if (!empty($filters['to_date']))    $query->where('date', '<=', $filters['to_date']);

        return $query->orderBy('date', 'desc')->paginate($filters['per_page'] ?? 20);
    }

    public function findById(int $id)
    {
        return $this->model->with(['teacher.user', 'status', 'recorder'])->findOrFail($id);
    }

    public function create(array $data): TeacherAttendance
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): TeacherAttendance
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);
        return $record->fresh(['teacher.user', 'status']);
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function getByTeacher(int $teacherId, array $filters = [])
    {
        $query = $this->model->with('status')
            ->where('teacher_id', $teacherId);

        if (!empty($filters['from_date'])) $query->where('date', '>=', $filters['from_date']);
        if (!empty($filters['to_date']))   $query->where('date', '<=', $filters['to_date']);

        return $query->orderBy('date', 'desc')->paginate($filters['per_page'] ?? 30);
    }

    public function getByDate(string $date)
    {
        return $this->model->with(['teacher.user', 'status'])
            ->where('date', $date)
            ->orderBy('teacher_id')
            ->get();
    }

    public function getTeacherStats(int $teacherId, array $filters = []): array
    {
        $base = fn() => $this->model->where('teacher_id', $teacherId)
            ->when(!empty($filters['from_date']), fn($q) => $q->where('date', '>=', $filters['from_date']))
            ->when(!empty($filters['to_date']),   fn($q) => $q->where('date', '<=', $filters['to_date']));

        $total   = (clone $base())->count();
        $present = (clone $base())->whereHas('status', fn($q) => $q->where('is_present', true))->count();
        $absent  = (clone $base())->whereHas('status', fn($q) => $q->where('code', 'A'))->count();
        $late    = (clone $base())->whereHas('status', fn($q) => $q->where('code', 'L'))->count();

        return [
            'total'           => $total,
            'present'         => $present,
            'absent'          => $absent,
            'late'            => $late,
            'attendance_rate' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
        ];
    }

    public function findByTeacherAndDate(int $teacherId, string $date)
    {
        return $this->model
            ->where('teacher_id', $teacherId)
            ->where('date', $date)
            ->first();
    }
}

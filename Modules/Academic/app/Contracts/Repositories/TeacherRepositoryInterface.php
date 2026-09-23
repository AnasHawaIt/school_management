<?php

namespace Modules\Academic\app\Contracts\Repositories;

use Modules\Academic\app\Entities\Teacher;

interface TeacherRepositoryInterface
{
    public function getAll(array $filters = []);

    public function findById(int $id): Teacher;

    public function create(array $data): Teacher;

    public function update(int $id, array $data): Teacher;

    public function delete(int $id): bool;

    public function restore(int $id): bool;

    public function getWithQualifications(int $id): Teacher;

    public function getTeacherTimetable(int $teacherId, int $semesterId);

    public function generateEmployeeId(): string;
}

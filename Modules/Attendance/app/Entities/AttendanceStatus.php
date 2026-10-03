<?php

namespace Modules\Attendance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceStatus extends Model
{
    protected $fillable = ['name', 'name_ar', 'code', 'color', 'is_present'];

    protected $casts = ['is_present' => 'boolean'];

    public function studentAttendances(): HasMany
    {
        return $this->hasMany(StudentAttendance::class, 'status_id');
    }

    public function teacherAttendances(): HasMany
    {
        return $this->hasMany(TeacherAttendance::class, 'status_id');
    }
}

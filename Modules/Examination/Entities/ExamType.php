<?php

namespace Modules\Examination\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamType extends Model
{
    protected $fillable = ['name', 'name_ar', 'weight', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }
}

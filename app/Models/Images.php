<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Images extends Model
{
    use softDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['path'];

    public function imageable()
    {
        return $this->morphTo();
    }
}

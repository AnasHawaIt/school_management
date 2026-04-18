<?php

namespace Modules\Library\Entities;

use App\Models\Images;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\Library\Database\Factories\AuthorFactory;

class Author extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = [];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function images()
    {
        return $this->morphMany(Images::class, 'imageable');
    }

    // protected static function newFactory(): AuthorFactory
    // {
    //     // return AuthorFactory::new();
    // }
}

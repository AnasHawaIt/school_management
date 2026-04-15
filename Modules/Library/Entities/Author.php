<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

// use Modules\Library\Database\Factories\AuthorFactory;

class Author extends Model
{
    use HasFactory,SoftDeletes,HasTranslations;

    public $translatable = ['name', 'description','birth_date','death_date',];

    protected $dates = ['deleted_at'];

    protected $guarded = [];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    // protected static function newFactory(): AuthorFactory
    // {
    //     // return AuthorFactory::new();
    // }
}

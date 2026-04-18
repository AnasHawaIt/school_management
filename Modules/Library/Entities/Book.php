<?php

namespace Modules\Library\Entities;

use App\Models\Images;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nwidart\Modules\Publishing\Publisher;

// use Modules\Library\Database\Factories\BookFactory;

class Book extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = [];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function images()
    {
        return $this->morphMany(Images::class, 'imageable');
    }


    // protected static function newFactory(): BookFactory
    // {
    //     // return BookFactory::new();
    // }
}

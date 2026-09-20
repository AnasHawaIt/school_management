<?php

namespace Modules\Transport\app\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Modules\Transport\Database\Factories\ImagesFactory;

class Images extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): ImagesFactory
    // {
    //     // return ImagesFactory::new();
    // }
}

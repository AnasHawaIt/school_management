<?php

namespace Modules\Transport\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Transport\Database\Factories\ImagesFactory;

class images extends Model
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

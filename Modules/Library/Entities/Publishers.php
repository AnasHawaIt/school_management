<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\Library\Database\Factories\PublishersFactory;

class Publishers extends Model
{
    use HasFactory,softDeletes;

    protected $dates = ['deleted_at'];


    protected $table = 'publishers';

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    // protected static function newFactory(): PublishersFactory
    // {
    //     // return PublishersFactory::new();
    // }
}

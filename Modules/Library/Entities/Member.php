<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\Library\Database\Factories\MemberFactory;

class Member extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];


    protected $fillable = ['name', 'email', 'phone'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // protected static function newFactory(): MemberFactory
    // {
    //     // return MemberFactory::new();
    // }
}

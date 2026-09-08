<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Entities\User;

// use Modules\Library\Database\Factories\MemberFactory;

class Member extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded=[];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function getMembershipStatusAttribute()
    {
        if ($this->status === 'suspended') {
            return 'suspended';
        }

        return $this->end_date && $this->end_date < now()
            ? 'expired'
            : 'active';
    }

    // protected static function newFactory(): MemberFactory
    // {
    //     // return MemberFactory::new();
    // }
}

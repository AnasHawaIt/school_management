<?php

namespace Modules\Announcement\Entities;

use App\Models\Images;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Entities\User;
use Modules\SMS\Entities\SmsLog;

class Announcement extends Model
{
    use SoftDeletes,HasFactory;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'body',
        'user_id',
        'is_active',
        'published_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function smsLogs()
    {
       return $this->hasMany(SmsLog::class);
    }
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());}

    public function images()
    {
        return $this->morphMany(Images::class, 'imageable');
    }
}

<?php

 namespace Modules\Messagings\app\Entities;

 use App\Models\Images;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;
 use Modules\Core\app\Entities\User;

 class MessageRecipient extends Model
 {
     use SoftDeletes,HasFactory;

     protected $dates = ['deleted_at'];

     protected $fillable = [
         'message_id',
         'recipient_id',
         'is_read',
         'read_at',
         'is_archived',
         'is_deleted',
     ];

     protected $casts = [
         'is_read' => 'boolean',
         'read_at' => 'datetime',
         'is_archived' => 'boolean',
         'is_deleted' => 'boolean',
     ];

     public function message()
     {
         return $this->belongsTo(
             Message::class,
             'message_id'
         );
     }


     public function recipient()
     {
         return $this->belongsTo(
             User::class,
             'recipient_id'
         );
     }

     public function statistic()
     {
         return $this->hasOne(MessageStatistic::class, 'message_recipient_id');
     }

     public function images()
     {
         return $this->morphMany(Images::class, 'imageable');
     }

     public function scopeUnread($query)
     {
         return $query->where('is_read', false);
     }

     public function scopeRead($query)
     {
         return $query->where('is_read', true);
     }

     public function scopeForUser($query, $userId)
     {
         return $query->where(
             'recipient_id',
             $userId
         );
     }

 }

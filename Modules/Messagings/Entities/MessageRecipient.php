<?php

 namespace Modules\Messagings\Entities;

 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;
 use Modules\Core\Entities\User;

 class MessageRecipient extends Model
 {
     use SoftDeletes,HasFactory;

     protected $dates = ['deleted_at'];

     protected $fillable = [
         'message_id',
         'recipient_id',
         'is_read',
         'read_at',
     ];

     protected $casts = [
         'is_read' => 'boolean',
         'read_at' => 'datetime',
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

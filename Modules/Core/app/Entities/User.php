<?php

namespace Modules\Core\app\Entities;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Academic\Entities\Guardian;
use Modules\Academic\Entities\Student;
use Modules\Academic\Entities\Teacher;
use Modules\Core\Database\Factories\UserFactory;
use Modules\Library\app\Entities\Member;
use Modules\Messagings\app\Entities\Conversation;
use Modules\Messagings\app\Entities\Message;
use Modules\Notifications\app\Entities\Notification;
use Modules\SMS\Entities\SmsOtp;

class User extends Authenticatable implements CanResetPassword
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens, CanResetPasswordTrait;
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'first_name',
        'last_name',
        'first_name_ar',
        'last_name_ar',
        'gender',
        'date_of_birth',
        'email',
        'password',
        'phone',
        'avatar',
        'user_type',
        'is_active',
        'email_verified_at',
        'fcm_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Relationships
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getNameAttribute(): string
    {
        return $this->full_name;
    }

    public function getFullNameArAttribute(): string
    {
        return "{$this->first_name_ar} {$this->last_name_ar}";
    }
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withTimestamps();
    }

    public function activityLogs()
    {
        return $this->hasMany(EventLogs::class);
    }
    public function teacher() { return $this->hasOne(Teacher::class); }
    public function student() { return $this->hasOne(Student::class); }
    public function parent()  { return $this->hasOne(Guardian::class); }
    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('user_type', $type);
    }

    public function scopeAdmins($query)
    {
        return $query->where('user_type', 'admin');
    }

    public function scopeTeachers($query)
    {
        return $query->where('user_type', 'teacher');
    }

    public function scopeStudents($query)
    {
        return $query->where('user_type', 'student');
    }

    public function scopeParents($query)
    {
        return $query->where('user_type', 'parent');
    }

    /**
     * Helper Methods
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();
    }

    public function assignRole(string $role): void
    {
        $roleModel = Role::where('name', $role)->first();
        if ($roleModel && !$this->hasRole($role)) {
            $this->roles()->attach($roleModel->id);
        }
    }

    public function removeRole(string $role): void
    {
        $roleModel = Role::where('name', $role)->first();
        if ($roleModel) {
            $this->roles()->detach($roleModel->id);
        }
    }

    public function getPermissions()
    {
        return $this->roles()->with('permissions')->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id');
    }

    public function isAdmin(): bool
    {
        return $this->user_type === 'admin' || $this->hasRole('admin');
    }

    public function isTeacher(): bool
    {
        return $this->user_type === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->user_type === 'student';
    }

    public function isParent(): bool
    {
        return $this->user_type === 'parent';
    }

    public function otps()
    {
        return $this->hasMany(SmsOtp::class);
    }

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function conversations()
    {
        return $this->belongsToMany(
            Conversation::class,
            'conversation_participants',
            'user_id',
            'conversation_id'
        )->withPivot([
            'conversation_role',
            'joined_at',
            'last_read_at',
            'is_muted',
            'is_archived',
        ]);
    }

    public function sentMessages()
    {
        return $this->hasMany(
            Message::class,
            'sender_id'
        );
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}

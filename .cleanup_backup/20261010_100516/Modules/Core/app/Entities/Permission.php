<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
class Permission extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()->useLogName('permission');
    }
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * Relationships
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role')
            ->withTimestamps();
    }

    /**
     * Scopes
     */
    public function scopeByName($query, string $name)
    {
        return $query->where('name', $name);
    }

    public function scopeByModule($query, string $module)
    {
        return $query->where('name', 'like', "{$module}.%");
    }

    /**
     * Helper Methods
     */
    public function getModule(): string
    {
        // Extract module from permission name
        // Example: "users.create" -> "users"
        $parts = explode('.', $this->name);
        return $parts[0] ?? 'other';
    }
}

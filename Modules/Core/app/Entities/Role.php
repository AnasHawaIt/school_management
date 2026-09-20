<?php

namespace Modules\Core\app\Entities;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * Relationships
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user')
            ->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role')
            ->withTimestamps();
    }

    /**
     * Scopes
     */
    public function scopeByName($query, string $name)
    {
        return $query->where('name', $name);
    }

    /**
     * Helper Methods
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('name', $permission)->exists();
    }

    public function givePermission(string $permission): void
    {
        $permissionModel = Permission::where('name', $permission)->first();
        if ($permissionModel && !$this->hasPermission($permission)) {
            $this->permissions()->attach($permissionModel->id);
        }
    }

    public function revokePermission(string $permission): void
    {
        $permissionModel = Permission::where('name', $permission)->first();
        if ($permissionModel) {
            $this->permissions()->detach($permissionModel->id);
        }
    }

    public function syncPermissions(array $permissions): void
    {
        $permissionIds = Permission::whereIn('name', $permissions)->pluck('id');
        $this->permissions()->sync($permissionIds);
    }
}

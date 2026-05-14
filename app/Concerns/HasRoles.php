<?php

namespace App\Concerns;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait HasRoles
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /** Pull all permissions through assigned roles (cached per request). */
    public function permissionsCollection(): Collection
    {
        $key = 'user_permissions_'.$this->getKey();

        return Cache::driver('array')->remember($key, 60, function () {
            $this->loadMissing('roles.permissions');

            return $this->roles
                ->flatMap(fn (Role $role) => $role->permissions)
                ->unique('id')
                ->values();
        });
    }

    public function hasRole(string|array $names): bool
    {
        $names = is_array($names) ? $names : [$names];

        return $this->roles->whereIn('name', $names)->isNotEmpty();
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->hasRole('super_admin')) {
            return true;
        }

        return $this->permissionsCollection()
            ->contains(fn (Permission $p) => $p->name === $permission);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function assignRole(int|string|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        } elseif (is_int($role)) {
            $role = Role::findOrFail($role);
        }

        $this->roles()->syncWithoutDetaching([$role->getKey()]);
    }

    public function syncRoles(array $roleIds): void
    {
        $this->roles()->sync($roleIds);
    }
}

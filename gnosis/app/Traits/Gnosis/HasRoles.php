<?php

namespace App\Traits\Gnosis;

use App\Models\Gnosis\Role;
use App\Models\Gnosis\Permission;

trait HasRoles
{
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function grantRole($role)
    {
        return $this->roles()->save(
            Role::whereName($role)->firstOrFail()
        );
    }

    public function revokeRole($role)
    {
        return $this->roles()
            ->wherePivot('name', '=', $role)
            ->detach();
    }

    public function revokeAllRoles()
    {
        return $this->roles()->sync([]);
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }
        return !! $role->intersect($this->roles)->count();
    }

    public function hasPermission(Permission $permission)
    {
        return $this->hasRole($permission->roles);
    }
}

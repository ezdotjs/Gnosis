<?php

namespace App\Models\Gnosis;

use Illuminate\Database\Eloquent\Model;
use App\Models\Gnosis\Permission;

class Role extends Model
{
    protected $fillable = [
        'name',
        'label',
        'visible',
        'protected'
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function grantPermission(Permission $permission)
    {
        return $this->permissions()->save($permission);
    }
}

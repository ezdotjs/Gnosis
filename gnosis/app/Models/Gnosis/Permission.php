<?php

namespace App\Models\Gnosis;

use Illuminate\Database\Eloquent\Model;
use App\Models\Gnosis\Role;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'label'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}

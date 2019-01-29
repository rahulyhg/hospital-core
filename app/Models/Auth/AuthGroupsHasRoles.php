<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $group_id
 * @property int $role_id
 */
class AuthGroupsHasRoles extends Model
{
    /**
     * @var array
     */
    protected $table='auth_groups_has_roles';
    protected $fillable = ['group_id', 'role_id'];
    public $timestamps = false;
}

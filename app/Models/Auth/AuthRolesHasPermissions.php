<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $role_id
 * @property int $permission_id
 */
class AuthRolesHasPermissions extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['role_id', 'permission_id'];

}

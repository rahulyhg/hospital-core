<?php

namespace App;

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
    protected $fillable = ['group_id', 'role_id'];

}

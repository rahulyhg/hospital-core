<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $group_id
 * @property int $user_id
 */
class AuthUsersGroups extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['group_id', 'user_id'];

}

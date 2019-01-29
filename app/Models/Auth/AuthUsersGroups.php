<?php

namespace App\Models\Auth;

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
    protected $table='auth_users_groups';
    protected $fillable = ['group_id', 'user_id','khoa_id','phong_id'];
    public $timestamps = false;

}

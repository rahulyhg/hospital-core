<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 */
class AuthRoles extends Model
{
    /**
     * @var array
     */
     protected $table='auth_roles'; 
    protected $fillable = ['id', 'name', 'description'];
    public $timestamps = false;

}

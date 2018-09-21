<?php

namespace App;

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
    protected $fillable = ['id', 'name', 'description'];

}

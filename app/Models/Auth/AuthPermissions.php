<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $authorized_uri
 */
class AuthPermissions extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['id', 'name', 'description', 'authorized_uri'];

}

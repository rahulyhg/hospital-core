<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 */
class AuthGroups extends Model
{
    /**
     * @var array
     */
    protected $table='auth_groups';     
    protected $fillable = ['name', 'description','meta_data'];
    public $timestamps = false;

}

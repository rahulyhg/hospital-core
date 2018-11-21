<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class AuthUsers extends Model
{
    protected $table='auth_users';
    protected $primaryKey='id';    
    protected $fillable = ['id', 'name', 'email','password','remember_token','created_at','updated_at','fullname','userstatus','khoa','chuc_vu'];

}

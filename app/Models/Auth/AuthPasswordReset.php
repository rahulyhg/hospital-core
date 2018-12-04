<?php 
namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

/**
 * @property varchar email
 * @property varchar token
 */
class AuthPasswordReset extends Model
{
    protected $table='password_resets';
    
    protected $fillable = [
        'email', 'token'
    ];
    
    public $timestamps = false;
}
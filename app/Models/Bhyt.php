<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bhyt extends Model
{
    //
    protected $table='bhyt';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

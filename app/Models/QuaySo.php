<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuaySo extends Model
{
    //
    protected $table='quay_so';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

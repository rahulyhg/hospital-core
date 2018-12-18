<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoThuNgan extends Model
{
    //
    protected $table='so_thu_ngan';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

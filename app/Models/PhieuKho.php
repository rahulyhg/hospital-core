<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhieuKho extends Model
{
    //
    protected $table='phieu_kho';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

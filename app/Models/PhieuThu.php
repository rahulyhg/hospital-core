<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhieuThu extends Model
{
    //
    protected $table='phieu_thu';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

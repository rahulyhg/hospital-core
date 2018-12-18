<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoPhieuThu extends Model
{
    //
    protected $table='so_phieu_thu';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

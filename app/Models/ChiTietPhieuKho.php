<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietPhieuKho extends Model
{
    //
    protected $table='chi_tiet_phieu_kho';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

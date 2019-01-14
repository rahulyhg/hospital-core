<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhMucKetQuaYLenh extends Model
{
    //
    protected $table='danh_muc_ket_qua_y_lenh';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

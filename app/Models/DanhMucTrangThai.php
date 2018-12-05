<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhMucTrangThai extends Model
{
    //
    protected $table='danh_muc_trang_thai';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

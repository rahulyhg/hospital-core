<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhMucTongHop extends Model
{
    //
    protected $table='danh_muc_tong_hop';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

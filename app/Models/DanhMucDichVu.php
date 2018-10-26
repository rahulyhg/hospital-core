<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhMucDichVu extends Model
{
    //
    protected $table='danh_muc_dich_vu';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

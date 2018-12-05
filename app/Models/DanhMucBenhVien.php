<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhMucBenhVien extends Model
{
    //
    protected $table='danh_muc_benh_vien';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

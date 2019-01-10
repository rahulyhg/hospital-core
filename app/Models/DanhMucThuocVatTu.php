<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhMucThuocVatTu extends Model
{
    //
    protected $table='danh_muc_thuoc_vat_tu';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhomDanhMuc extends Model
{
    protected $table='nhom_danh_muc';
    
    protected $primaryKey = 'id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

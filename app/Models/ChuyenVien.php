<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChuyenVien extends Model
{
    //
    protected $table='chuyen_vien';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

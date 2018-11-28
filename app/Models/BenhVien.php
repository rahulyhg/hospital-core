<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BenhVien extends Model
{
    //
    protected $table='benh_vien';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

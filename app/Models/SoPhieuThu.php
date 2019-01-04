<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoPhieuThu extends Model
{
    use SoftDeletes;
    
    protected $table='so_phieu_thu';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
    
    protected $dates = ['deleted_at'];
}

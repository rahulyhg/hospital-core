<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaVien extends Model
{
    //
    protected $table='ra_vien';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

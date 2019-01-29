<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VienPhi extends Model
{
    //
    protected $table='vien_phi';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

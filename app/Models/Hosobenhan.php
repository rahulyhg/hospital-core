<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hosobenhan extends Model
{
    //
    protected $table='hosobenhan';
    
    protected $primaryKey='hosobenhanid';
    
    //public function patient(){
        //return $this->belongsTo('App\Models\Patient');
        //return $this->hasMany('App\Models\Patient');
    //}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    //
    protected $table='patient';
    
    protected $primaryKey='patientid';
    
    //public function hosobenhan(){
      //  return $this->hasMany('App\Models\Hosobenhan','patientid');
        //return $this->belongsTo('App\Models\Hosobenhan');
   //}
}

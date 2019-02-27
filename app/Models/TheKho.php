<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TheKho extends Model
{
    //
    protected $table='the_kho';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}

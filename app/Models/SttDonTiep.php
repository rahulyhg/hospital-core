<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SttDonTiep extends Model
{
    //protected $fillable = [];
    
    protected $guarded = ['id'];
    
    protected $table = 'stt_don_tiep';
    
    public $timestamps = false;
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedSttDontiep extends Model
{
    protected $fillable = [
        'loai_stt',
        'sothutunumber',
        'trangthai',
        'ngayphat',
        'khuvuc',
    ];
    
    protected $dates = [
        'ngayphat',
        'ngaygoi',
    ];
    
    protected $table = 'red_stt_dontiep';
}

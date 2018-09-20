<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedSttDontiep extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'loai_stt',
        'so_thu_tu',
        'trang_thai',
        'ma_so_kiosk',
        'id_phong',
        'id_benh_vien',
        'thong_tin_so_bo',
        'thoi_gian_phat',
        'thoi_gian_goi',
        'thoi_gian_ket_thuc'
    ];
    
    protected $dates = [
        //'thoi_gian_phat',
        //'thoi_gian_goi',
        //'thoi_gian_ket_thuc'
        ];
    
    protected $table = 'red_stt_dontiep';
}

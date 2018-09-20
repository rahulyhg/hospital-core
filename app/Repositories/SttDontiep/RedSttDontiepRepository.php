<?php

namespace App\Repositories\SttDontiep;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\RedSttDontiep;

class RedSttDontiepRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return RedSttDontiep::class;
    }
    
    public function getInfoPatientByStt($stt, $id_phong, $id_benh_vien)
    {
        $dieu_kien = [
            'loai_stt'      => $stt[0],
            'so_thu_tu'     => (int)substr($stt, 1, 4),
            'id_phong'      => $id_phong,
            'id_benh_vien'  => $id_benh_vien
        ];
        
        $data = DB::table('red_stt_dontiep')
                ->where($dieu_kien)
                ->orderBy('id', 'desc')
                ->first();
                
        return $data;   
    }
}
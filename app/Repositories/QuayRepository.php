<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepository;

class QuayRepository extends BaseRepository
{
    
    public function getListQuay($khuVucId,$benhVienId)
    {
        $dataSet = DB::table('quay_so')
                ->where([
                    'khu_vuc_id'=>$khuVucId,
                    'benh_vien_id'=>$benhVienId
                    ])
                ->get();
        return $dataSet;    
        
    }
}

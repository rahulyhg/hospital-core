<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\QuaySo;

class QuayRepository extends BaseRepositoryV2
{

    public function getModel()
    {
        return QuaySo::class;
    }    
    
    public function getListQuay($khuVucId,$benhVienId)
    {
        $dataSet = $this->model
                ->where([
                    'khu_vuc_id'=>$khuVucId,
                    'benh_vien_id'=>$benhVienId
                    ])
                ->get();
        return $dataSet;    
        
    }
}

<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepository;

class QuayRepository extends BaseRepository
{
    
    public function getListQuay($khuVucId,$benhVienId)
    {
        $dataSet = $this->model->where([
                                    'khu_vuc_id'=>$khuVucId,
                                    'benh_vien_id'=>$benhVienId
                                ])
                                ->get();
        return $dataSet;    
        
    }
}

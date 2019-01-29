<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\KhuVuc;

class KhuVucRepository extends BaseRepositoryV2
{
    
    public function getModel()
    {
        return KhuVuc::class;
    }    
    
    public function getListKhuVuc($loai,$benhVienId)
    {
        $dataSet = $this->model
                ->where([
                    'loai'=>$loai,
                    'benh_vien_id'=>$benhVienId,
                    ])
                ->get();
        return $dataSet;    
        
    }
}

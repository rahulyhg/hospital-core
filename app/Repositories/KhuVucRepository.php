<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepository;

class KhuVucRepository extends BaseRepository
{
    
    public function getListKhuVuc($loai,$benhVienId)
    {
        $dataSet = DB::table('khu_vuc')
                ->where([
                    'loai'=>$loai,
                    'benh_vien_id'=>$benhVienId,
                    ])
                ->get();
        return $dataSet;    
        
    }
}

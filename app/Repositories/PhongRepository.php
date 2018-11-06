<?php
namespace App\Repositories;

use DB;
use App\Models\Phong;
use App\Repositories\BaseRepositoryV2;

class PhongRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return Phong::class;
    }
    
    public function getListPhong($loaiPhong,$khoaId)
    {
        $phong = DB::table('phong')
                ->where([
                    'loai_phong'=>$loaiPhong,
                    'khoa_id'=>$khoaId])
                ->orderBy('ten_phong')
                ->get();
        return $phong;    
    }
    
    
    
}
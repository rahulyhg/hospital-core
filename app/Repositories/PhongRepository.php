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
                    'khoa_id'=>$khoaId,
                    'loai_benh_an'=>24,
                    'trang_thai'=>1
                    ])
                ->orderBy('ten_nhom')
                ->distinct()
                ->get(['ten_nhom','ma_nhom']);
        return $phong;    
    }
}
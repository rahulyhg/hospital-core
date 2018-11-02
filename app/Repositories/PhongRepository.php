<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepository;

class PhongRepository extends BaseRepository
{

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
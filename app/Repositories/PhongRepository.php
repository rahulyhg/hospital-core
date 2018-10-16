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
                    'khoa_id'=>$khoaId])
                ->orderBy('ten_phong')
                ->get();
        return $phong;    
    }
    
}
<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepository;

class PhongRepository extends BaseRepository
{

     public function getListPhong($loaiphong,$khoaid)
    {
        $phong = DB::table('phong')
                ->where([
                    'loai_phong'=>$loaiphong,
                    'khoa_id'=>$khoaid
                ])
                ->orderBy('ten_phong')
                ->get();
        return $phong;    
    }
    
}
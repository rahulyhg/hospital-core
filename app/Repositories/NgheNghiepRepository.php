<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepository;

class NgheNghiepRepository extends BaseRepository
{

     public function getDataListNgheNghiep($request)
    {
        $nghenghiep = DB::table('nghenghiep')
                ->orderBy('nghenghiepdbid')
                ->get();
        return $nghenghiep;    
    }
    
}
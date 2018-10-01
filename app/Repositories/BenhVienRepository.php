<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepository;

class BenhVienRepository extends BaseRepository
{

     public function getDataListBenhVien($request)
    {
        $benhvien = DB::table('benhvien')
                ->orderBy('benhvienid')
                ->get();
        return $benhvien;    
    }
    
}
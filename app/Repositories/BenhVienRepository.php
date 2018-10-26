<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepository;

class BenhVienRepository extends BaseRepository
{

    public function listBenhVien()
    {
        $dataSet = DB::table('benh_vien')
                ->orderBy('id')
                ->get();
        return $dataSet;    
    }
    
}
<?php
namespace App\Repositories\DanhMuc;
use DB;
use App\Repositories\BaseRepository;

class DanhMucBenhVienRepository extends BaseRepository
{

    public function getDanhMucBenhVien()
    {
        $dataSet = DB::table('danh_muc_benh_vien')
                ->orderBy('ma_kcbbd')
                ->get();
        return $dataSet;    
    }
    
}
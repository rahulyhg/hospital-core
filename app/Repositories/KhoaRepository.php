<?php
namespace App\Repositories;

use DB;
use App\Models\Khoa;
use App\Repositories\BaseRepositoryV2;

class KhoaRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return Khoa::class;
    }
    
    public function getListKhoa($loaiKhoa, $benhVienId)
    {
        $data = $this->model->where([
                    'loai_khoa'     =>  $loaiKhoa,
                    'benh_vien_id'  =>  $benhVienId
                ])
                ->orderBy('ten_khoa')
                ->get();
        return $data;    
    }
    
}
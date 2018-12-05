<?php
namespace App\Repositories\DanhMuc;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\DanhMucBenhVien;

class DanhMucBenhVienRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return DanhMucBenhVien::class;
    }    

    public function getDanhMucBenhVien()
    {
        $dataSet = $this->model
                ->orderBy('ma_kcbbd')
                ->get();
        return $dataSet;    
    }
    
}
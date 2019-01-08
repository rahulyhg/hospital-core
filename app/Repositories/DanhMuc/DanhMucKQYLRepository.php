<?php
namespace App\Repositories\DanhMuc;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\DanhMucKetQuaYLenh;

class DanhMucKQYLRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return DanhMucKetQuaYLenh::class;
    }    

    public function getKetQuaYLenhByCode($maYLenh)
    {
        $column = [
            'id',
            'ma_nhom',
            'loai',
            'loai_nhom',
            'ma',
            'ten',
            'min as gh_duoi',
            'max as gh_tren',
            'don_vi_tinh'
            ];
        $dataSet = $this->model
                ->where('ma_nhom',$maYLenh)
                ->get($column);
        return $dataSet;    
    }
    
}
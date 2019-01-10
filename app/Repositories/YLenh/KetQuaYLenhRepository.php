<?php
namespace App\Repositories\YLenh;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\KetQuaYLenh;

class KetQuaYLenhRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return KetQuaYLenh::class;
    }
    
    public function getKetQuaYLenhByCode($maYLenh)
    {
        $dataSet = $this->model->where('ma_y_lenh',$maYLenh)->get();
        if($dataSet)
            return $dataSet;
        else
            return [];
    }    
}
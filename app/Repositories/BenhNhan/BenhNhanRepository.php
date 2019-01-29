<?php
namespace App\Repositories\BenhNhan;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\BenhNhan;


class BenhNhanRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return BenhNhan::class;
    }
    
    public function createDataBenhNhan(array $input)
    {
         $id = $this->model->create($input)->id;
         return $id;
    }
    
    public function checkMaSoBenhNhan($benh_nhan_id)
    {
        $column = [
            'id as benh_nhan_id', 
        ];
        $result = $this->model->where('benh_nhan.id', $benh_nhan_id)
                            ->get($column)
                            ->first(); 
        return $result;
    }
    
}
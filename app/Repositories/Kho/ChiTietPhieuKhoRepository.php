<?php
namespace App\Repositories\Kho;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\ChiTietPhieuKho;

class ChiTietPhieuKhoRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return ChiTietPhieuKho::class;
    }  
    
    public function createChiTietPhieuKho(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
}
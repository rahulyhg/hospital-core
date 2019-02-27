<?php
namespace App\Repositories\Kho;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\PhieuKho;

class PhieuKhoRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return PhieuKho::class;
    }  
    
    public function createPhieuKho(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
}
<?php
namespace App\Repositories;
use DB;
use App\Models\ChuyenVien;
use App\Repositories\BaseRepositoryV2;

class ChuyenVienRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return ChuyenVien::class;
    }
    
    public function createData(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }    
    

}
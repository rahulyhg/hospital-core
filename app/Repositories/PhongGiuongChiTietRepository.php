<?php
namespace App\Repositories;

use App\Repositories\BaseRepositoryV2;
use App\Models\PhongGiuongChiTiet;

class PhongGiuongChiTietRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return PhongGiuongChiTiet::class;
    }
    
    public function getById($id)
    {
        $result = $this->model->find($id); 
        return $result;
    }
  
    public function create(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
}
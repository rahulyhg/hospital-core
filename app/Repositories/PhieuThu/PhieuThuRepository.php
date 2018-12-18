<?php
namespace App\Repositories\PhieuThu;

use App\Repositories\BaseRepositoryV2;
use App\Models\PhieuThu;

class PhieuThuRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return PhieuThu::class;
    }
    
    public function getListPhieuThu() {
        $data = $this->model->all();
        return $data;
    }
    
    public function getListPhieuThuBySoPhieuThuId($id) {
        $data = $this->model->where('so_phieu_thu_id', $id)->get();
        return $data;
    }
  
    public function createDataPhieuThu(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
}
<?php
namespace App\Repositories\PhieuThu;

use App\Repositories\BaseRepositoryV2;
use App\Models\SoPhieuThu;

class SoPhieuThuRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return SoPhieuThu::class;
    }
    
    public function getListSoPhieuThu($maSo, $loaiSo) {
        $query = $this->model->where('ma_so', 'like', '%' . $maSo . '%');
        if($loaiSo != "") {
            $loaiSoWhere = explode(",", $loaiSo);
            $query->whereIn('loai_so', $loaiSoWhere);
        }
        
        $data = $query->get();
        return $data;
    }
  
    public function createDataSoPhieuThu(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function updateSoPhieuThu($id, array $input)
    {
        $data = $this->model->findOrFail($id);
		$data->update($input);
    }
    
    public function deleteSoPhieuThu($id)
    {
        $this->model->destroy($id);
    }
}
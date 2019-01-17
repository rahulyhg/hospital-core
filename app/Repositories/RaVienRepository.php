<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\RaVien;

class RaVienRepository extends BaseRepositoryV2
{
    
    public function getModel()
    {
        return RaVien::class;
    }    
    
    public function getById($hsbaKhoaPhongId, $benhNhanId)
    {
        $where = [
            ['hsba_khoa_phong_id', '=', $hsbaKhoaPhongId],
            ['benh_nhan_id', '=', $benhNhanId]
        ];
        
        $result = $this->model->where($where)->get()->first();
        return $result;
        
    }
    
    public function createRaVien(array $input)
    {
         $id = $this->model->create($input)->id;
         return $id;
    }
    
    public function updateRaVien($id)
    {
        $hsbaKhoaPhong = $this->model->findOrFail($hsbaKhoaPhongId);
		$hsbaKhoaPhong->update($params);  
    }    
}

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
        $column = [
            'phieu_thu.id',
            'auth_users.fullname',
            'so_phieu_thu.ma_so',
            'phieu_thu.loai_phieu_thu_id',
            'phieu_thu.ngay_tap',
            'phieu_thu.vien_phi_id',
            'phieu_thu.ten_benh_nhan',
            'phieu_thu.ghi_chu',
            'phieu_thu.tong_tien',
            'phieu_thu.da_tra',
            'phieu_thu.con_no',
        ];
        
        $query = $this->model->where('so_phieu_thu_id', $id);
        $query->join('auth_users', 'phieu_thu.auth_users_id', '=', 'auth_users.id');
        $query->join('so_phieu_thu', 'phieu_thu.so_phieu_thu_id', '=', 'so_phieu_thu.id');
        $data = $query->get();
        
        $result = [
            'data'          => $data
        ];
        return $result;
    }
    
    public function getListPhieuThuByHsbaId($hsbaId) {
        $column = [
            'phieu_thu.ma_so',
            'phieu_thu.loai_phieu_thu_id',
            'phieu_thu.tong_tien',
            'phieu_thu.ly_do_mien_giam',
        ];
        
        $query = $this->model->where('hsba_id', $hsbaId);
        $data = $query->orderBy('ma_so')->get();
        
        $result = [
            'data'          => $data
        ];
        return $result;
    }
  
    public function createDataPhieuThu(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
}
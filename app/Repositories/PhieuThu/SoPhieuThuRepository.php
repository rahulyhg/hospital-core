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
    
    public function getListSoPhieuThu($maSo, $trangThai) {
        $column = [
            'so_phieu_thu.id',
            'auth_users.fullname',
            'so_phieu_thu.ma_so',
            'so_phieu_thu.ngay_tao',
            'so_phieu_thu.loai_so',
            'so_phieu_thu.tong_so_phieu',
            'so_phieu_thu.so_phieu_su_dung',
            //'so_phieu_thu.deleted_at',
        ];
        
        $query = $this->model->where('ma_so', 'like', '%' . $maSo . '%');
        if($trangThai != "") {
            $trangThaiWhere = explode(",", $trangThai);
            $query->whereIn('trang_thai', $trangThaiWhere);
        }
        $query->join('auth_users', 'so_phieu_thu.auth_users_id', '=', 'auth_users.id');
        $query->select($column);
        $data = $query->get();
        $result = [
            'data'          => $data
        ];
        return $result;
    }
    
    public function getSoPhieuThuById($id)
    {
        $result = $this->model->find($id); 
        return $result;
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
        $this->model->where('id', $id)->delete();
    }
    
    public function getSoPhieuThuByAuthUserIdAndTrangThai($auth_users_id) {
        $result = $this->model
                        ->where('auth_users_id', $auth_users_id)
                        ->where('trang_thai', 0)
                        ->orderBy('id')
                        ->first(); 
        return $result;
    }
}
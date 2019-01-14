<?php
namespace App\Repositories\PhieuThu;

use App\Repositories\BaseRepositoryV2;
use App\Models\PhieuThu;

class PhieuThuRepository extends BaseRepositoryV2
{
    const PHIEU_THU = 'phieu_thu';
    const PT_THU_TIEN = 0;
    const PT_HOAN_UNG = 1;
    const PT_TAM_UNG = 2;
    
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
        $where = [
            ['phieu_thu.hsba_id', '=', $hsbaId]
        ];

        $column = [
            'phieu_thu.id',
            'phieu_thu.ma_so',
            'phieu_thu.loai_phieu_thu_id',
            'phieu_thu.da_tra',
            'phieu_thu.mien_giam',
            'phieu_thu.ly_do_huy',
        ];

        $result = $this->model
                        ->leftJoin('danh_muc_trang_thai as dmtt', function($join) {
                            $join->whereRaw('cast(dmtt.gia_tri as integer) = phieu_thu.loai_phieu_thu_id')
                                ->where('dmtt.khoa', '=', self::PHIEU_THU);
                        })
                        ->where($where)
                        ->orderBy('ma_so')
                        ->get($column);
             
        $bill['tamUng'] = 0;
        $bill['hoanUng'] = 0;
        $bill['daNop'] = 0;
        $bill['mienGiam'] = 0;           
        if($result) {
            foreach($result as $item) {
                if($item->loai_phieu_thu_id == self::PT_TAM_UNG) {
                    $bill['tamUng'] += $item->da_tra;
                }
                if($item->loai_phieu_thu_id == self::PT_HOAN_UNG) {
                    $bill['hoanUng'] += $item->da_tra;
                }
                if($item->loai_phieu_thu_id == self::PT_THU_TIEN) {
                    $bill['daNop'] += $item->da_tra;
                    $bill['mienGiam'] += $item->mien_giam;
                }
            }
        }
        
        $data['data'] = $result;
        $data['bill'] = $bill;

        return $data;
    }
  
    public function createDataPhieuThu(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
}
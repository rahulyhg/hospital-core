<?php

namespace App\Services;

use App\Models\YLenh;
use App\Repositories\YLenh\YLenhRepository;
use App\Repositories\PhieuYLenh\PhieuYLenhRepository;
use App\Repositories\DanhMuc\DanhMucKQYLRepository;
use App\Repositories\YLenh\KetQuaYLenhRepository;
use App\Repositories\Auth\AuthUsersRepository;
use App\Repositories\DanhMuc\DanhMucDichVuRepository;
use App\Repositories\VienPhi\VienPhiRepository;
use Illuminate\Http\Request;
use Validator;
use DB;
use Carbon\Carbon;

class YLenhService {
    const PHIEU_DIEU_TRI = 3;
    
    public function __construct(YLenhRepository $yLenhRepository, PhieuYLenhRepository $phieuYLenhRepository,DanhMucKQYLRepository $danhMucKQYLRepository,KetQuaYLenhRepository $ketQuaYLenhRepository,AuthUsersRepository $authUsersRepository,DanhMucDichVuRepository $danhMucDichVuRepository,VienPhiRepository $vienPhiRepository)
    {
        $this->yLenhRepository = $yLenhRepository;
        $this->phieuYLenhRepository = $phieuYLenhRepository;
        $this->danhMucKQYLRepository=$danhMucKQYLRepository;
        $this->ketQuaYLenhRepository=$ketQuaYLenhRepository;
        $this->authUsersRepository=$authUsersRepository;
        $this->danhMucDichVuRepository=$danhMucDichVuRepository;
        $this->vienPhiRepository = $vienPhiRepository;
    }

    public function saveYLenh(array $input)
    {
        $array = [];
        
        $result = DB::transaction(function() use ($input, $array) {
            try {
                //insert table phieu_y_lenh
                if(isset($input['dataYLenh']))
                    $input = array_except($input, ['dataYLenh', 'username', 'icd10code']);
                $phieuYLenhParams = $input;
                $phieuYLenhParams = array_except($phieuYLenhParams, ['hsba_khoa_phong_id', 'data', 'doi_tuong_benh_nhan', 'muc_huong', 'loai_vien_phi', 'ms_bhyt']);
                $phieuYLenhParams['loai_phieu_y_lenh'] = self::PHIEU_DIEU_TRI;
                $phieuYLenhParams['trang_thai'] = 0;
                $phieuYLenhId = $this->phieuYLenhRepository->getPhieuYLenhId($phieuYLenhParams);
                
                //insert table y_lenh
                if($input['data']) {
                    foreach($input['data'] as $value) {
                        $array[] = [
                            'vien_phi_id'           => $input['vien_phi_id'],
                            'phieu_y_lenh_id'       => $phieuYLenhId,
                            'doi_tuong_benh_nhan'   => $input['doi_tuong_benh_nhan'],
                            'khoa_id'               => $input['khoa_id'],
                            'phong_id'              => $input['phong_id'],
                            'ma'                    => $value['ma'],
                            'ten'                   => $value['ten'],
                            'ten_bhyt'              => $value['ten_bhyt'] ?? null,
                            'ten_nuoc_ngoai'        => $value['ten_nuoc_ngoai'] ?? null,
                            'trang_thai'            => 0,
                            'gia'                   => $value['gia'],
                            'gia_bhyt'              => $value['gia_bhyt'],
                            'gia_nuoc_ngoai'        => $value['gia_nuoc_ngoai'],
                            'so_luong'              => $value['so_luong'],
                            'loai_y_lenh'           => $value['loai_nhom'],
                            'thoi_gian_chi_dinh'    => Carbon::now()->toDateTimeString(),
                            'bhyt_tra'              => $value['bhyt_tra'],
                            'vien_phi'              => $value['vien_phi'],
                            'muc_huong'             => $input['muc_huong'],
                            'loai_thanh_toan_cu'    => $input['loai_vien_phi'],
                            'loai_thanh_toan_moi'   => $input['loai_vien_phi'],
                            'ms_bhyt'               => $input['ms_bhyt']
                        ];
                    }
                }
                $this->yLenhRepository->saveYLenh($array);
                
                return true;
            } catch (\Exception $ex) {
                throw $ex;
            }
        });
        
        return $result;
    }
    
    public function getLichSuYLenh(array $input)
    {
        $result = $this->yLenhRepository->getLichSuYLenh($input);
        return $result;
    }
    
    public function getLichSuThuocVatTu(array $input)
    {
        $result = $this->yLenhRepository->getLichSuThuocVatTu($input);
        return $result;
    }
    
    public function getYLenhByHsbaId($hsbaId) {
        $result = $this->yLenhRepository->getYLenhByHsbaId($hsbaId);
        return $result;
    }

    public function getDetailPhieuYLenh($phieuYLenhId,$type)
    {
        $result = $this->yLenhRepository->getDetailPhieuYLenh($phieuYLenhId,$type);
        foreach($result as $item){
            $ketQuaYLenh = $this->ketQuaYLenhRepository->getKetQuaYLenhByCode($item->ma);
            foreach($ketQuaYLenh as $itemKQYL){
                $danhMucKetQua = $this->danhMucKQYLRepository->getDanhMucKetQuaByCode($itemKQYL->ma_ket_qua_y_lenh);
                $item['children']=$danhMucKetQua;                
            }
        }
        return $result;
    } 
    
    public function countItemYLenh($hsbaId)
    {
        $result = $this->yLenhRepository->countItemYLenh($hsbaId);
        return $result;
    }
    
    public function countItemThuocVatTu($hsbaId)
    {
        $result = $this->yLenhRepository->countItemThuocVatTu($hsbaId);
        return $result;
    }
    
    public function getListYLenhByVienPhiId($vienPhiId,$keyWords)
    {
        $loaiYLenh = array(1,2,3,4);
        $result = $this->yLenhRepository->getListYLenhByVienPhiId($vienPhiId,$keyWords);
        
        foreach($result as $item){
            $authUser = $this->authUsersRepository->getInforAuthUserById($item->auth_users_id);
            $item['auth_users_name']=$authUser?$authUser->fullname:null;
            if(in_array($item->loai_y_lenh,$loaiYLenh)){
                $dataDmdv=$this->danhMucDichVuRepository->getDichVuByCode($item->ma);
                $item['don_vi_tinh']=$dataDmdv?$dataDmdv->don_vi_tinh:null;
                $item['ma_nhom_dich_vu']=$dataDmdv?$dataDmdv->ten_nhom:null;
            }
        }
        
        return $result;
    }
    
    public function updateYLenhById($yLenhId,array $input)
    {
        $this->yLenhRepository->updateYLenhById($yLenhId, $input);
    }
    
    public function getAllCanLamSang($hsbaId)
    {
        $data = $this->vienPhiRepository->getAllCanLamSang($hsbaId);
        return $data;
    }    
}
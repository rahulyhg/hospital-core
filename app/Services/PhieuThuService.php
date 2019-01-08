<?php

namespace App\Services;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Repositories\PhieuThu\PhieuThuRepository;
use App\Repositories\PhieuThu\SoPhieuThuRepository;
use App\Repositories\YLenh\YLenhRepository;

class PhieuThuService {
  
    public function __construct(
        SoPhieuThuRepository $soPhieuThuRepository, 
        PhieuThuRepository $phieuThuRepository,
        YLenhRepository $yLenhRepository
    )
    {
        $this->phieuThuRepository = $phieuThuRepository;
        $this->soPhieuThuRepository = $soPhieuThuRepository;
        $this->yLenhRepository = $yLenhRepository;
    }
  
    public function createPhieuThu(array $input)
    {
        $soPhieuThuItem = $this->soPhieuThuRepository->getSoPhieuThuByAuthUserIdAndTrangThai($input['auth_users_id']);
        $input['so_phieu_thu_id'] = $soPhieuThuItem->id;
        $input['ma_so'] = $soPhieuThuItem->so_phieu_su_dung + 1;
        $input['ngay_tao'] = Carbon::now();
        $id = $this->phieuThuRepository->createDataPhieuThu($input);
        
        // Update so phieu thu
        $dataSoPhieuThu['so_phieu_su_dung'] = $input['ma_so'];
        $this->soPhieuThuRepository->updateSoPhieuThu($soPhieuThuItem->id, $dataSoPhieuThu);
        
        // Update Y Lenh
        $dataYLenh['phieu_thu_id'] = $id;
        $this->yLenhRepository->updatePhieuThuIdByHsbaId($input['hsba_id'], $dataYLenh);
        return $id;
    }
    
    public function getListPhieuThu() {
        $data = $this->phieuThuRepository->phieuThuRepository();
        return $data;
    }
    
    public function getListPhieuThuBySoPhieuThuId($id) {
        $data = $this->phieuThuRepository->getListPhieuThuBySoPhieuThuId($id);
        return $data;
    }
    
    public function getListPhieuThuByHsbaId($hsbaId) {
        $data = $this->phieuThuRepository->getListPhieuThuByHsbaId($hsbaId);
        return $data;
    }
}
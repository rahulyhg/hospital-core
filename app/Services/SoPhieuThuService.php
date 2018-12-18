<?php

namespace App\Services;

use App\Repositories\PhieuThu\SoPhieuThuRepository;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

class SoPhieuThuService {
  
    public function __construct(SoPhieuThuRepository $soPhieuThuRepository)
    {
        $this->soPhieuThuRepository = $soPhieuThuRepository;
    }
  
    public function createSoPhieuThu(array $input)
    {
        $input['ngay_tao'] = Carbon::now();
        $id = $this->soPhieuThuRepository->createDataSoPhieuThu($input);
        return $id;
    }
    
    public function getListSoPhieuThu($maSo, $loaiSo) {
        $data = $this->soPhieuThuRepository->getListSoPhieuThu($maSo, $loaiSo);
        return $data;
    }
    
    public function updateSoPhieuThu($id, array $input)
    {
        $this->soPhieuThuRepository->updateSoPhieuThu($id, $input);
    }
    
    public function deleteSoPhieuThu($id)
    {
        $this->soPhieuThuRepository->deleteSoPhieuThu($id);
    }
}
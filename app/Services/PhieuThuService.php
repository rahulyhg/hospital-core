<?php

namespace App\Services;

use App\Repositories\PhieuThu\PhieuThuRepository;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

class PhieuThuService {
  
    public function __construct(PhieuThuRepository $phieuThuRepository)
    {
        $this->phieuThuRepository = $phieuThuRepository;
    }
  
    public function createPhieuThu(array $input)
    {
        $input['ngay_tao'] = Carbon::now();
        $id = $this->phieuThuRepository->createDataPhieuThu($input);
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
}
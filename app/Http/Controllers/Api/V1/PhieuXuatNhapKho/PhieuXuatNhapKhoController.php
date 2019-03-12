<?php
namespace App\Http\Controllers\Api\V1\PhieuXuatNhapKho;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\PhieuKhoService;

class PhieuXuatNhapKhoController extends APIController
{
    public function __construct(PhieuKhoService $phieuKhoService)
    {
        $this->phieuKhoService = $phieuKhoService;
    }
    public function getListPhieuKhoByKhoIdXuLy(Request $request)
    {
        $startDay = $request->query('startDay');
        $endDay = $request->query('endDay');
        $khoIdXuLy = $request->query('khoIdXuLy');
        $data = $this->phieuKhoService->getListPhieuKhoByKhoIdXuLy($startDay,$endDay,$khoIdXuLy);
        return $this->respond($data);
    }
    
    public function createPhieuXuat(Request $request)
    {
        $phieuKhoId = $request->query('phieuKhoId');
        $nhanVienDuyetId = $request->query('nhanVienDuyetId');        
        $data = $this->phieuKhoService->createPhieuXuat($phieuKhoId,$nhanVienDuyetId);
        return $this->respond([]);
    }
    
    public function createPhieuNhap(Request $request)
    {
        $phieuKhoId = $request->query('phieuKhoId');
        $nhanVienDuyetId = $request->query('nhanVienDuyetId');        
        $data = $this->phieuKhoService->createPhieuNhap($phieuKhoId,$nhanVienDuyetId);
        return $this->respond([]);
    }
    
    public function getChiTietPhieuXuatNhap($phieuKhoId)
    {
        $data = $this->phieuKhoService->getChiTietPhieuXuatNhap($phieuKhoId);
        return $this->respond($data);
    }    
}
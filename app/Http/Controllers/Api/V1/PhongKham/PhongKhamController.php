<?php
namespace App\Http\Controllers\Api\V1\PhongKham;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\HsbaKhoaPhongService;
use App\Services\SttPhongKhamService;
use App\Services\DieuTriService;
use App\Services\Icd10Service;
use App\Services\YLenhService;

class PhongKhamController extends APIController
{
    public function __construct
    (
        HsbaKhoaPhongService $hsbaKhoaPhongService, 
        SttPhongKhamService $sttPhongKhamService, 
        DieuTriService $dieuTriService, 
        Icd10Service $icd10Service,
        YLenhService $yLenhService
    )
    {
        $this->hsbaKhoaPhongService = $hsbaKhoaPhongService;
        $this->sttPhongKhamService = $sttPhongKhamService;
        $this->dieuTriService = $dieuTriService;
        $this->icd10Service = $icd10Service;
        $this->yLenhService = $yLenhService;
    }
    
    public function update($hsbaKhoaPhongId, Request $request)
    {
        try {
            $isNumeric = is_numeric($hsbaKhoaPhongId);
            
            if($isNumeric) {
                $input = $request->all();
                $this->hsbaKhoaPhongService->update($hsbaKhoaPhongId, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        }
    }
    
    public function getById($hsbaKhoaPhongId)
    {
        $isNumeric = is_numeric($hsbaKhoaPhongId);
        
        if($isNumeric) {
            $data = $this->hsbaKhoaPhongService->getById($hsbaKhoaPhongId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function updateInfoDieuTri(Request $request)
    {
        try 
        {
            $input = $request->all();
            $input = $request->except('bmi');
            $this->dieuTriService->updateInfoDieuTri($input);
            $this->setStatusCode(201);
            
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        }
    }
  
    public function getListPhongKham($hsbaId)
    {
        $isNumeric = is_numeric($hsbaId);
        
        if($isNumeric) {
            $data = $this->sttPhongKhamService->getListPhongKham($hsbaId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function xuTriBenhNhan(Request $request)
    {
        $input = $request->all();
        $data = $this->dieuTriService->xuTriBenhNhan($input);
        
        if(!$data) {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function chuyenKhoaPhong(Request $request)
    {   
        $input = $request->all();
        $data = $this->dieuTriService->createChuyenPhong($input);
        
        if(!$data) {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getIcd10ByCode($icd10Code)
    {
        $data = $this->icd10Service->getIcd10ByCode($icd10Code);
        
        if(!$data) {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function saveYLenh(Request $request)
    {
        $input = $request->all();
        $phieuDieuTri = $this->dieuTriService->getPhieuDieuTri($input);
        
        if($phieuDieuTri) {
            $input['dieu_tri_id'] = $phieuDieuTri->id;
            $bool = $this->yLenhService->saveYLenh($input);
            
            if($bool) {
                $this->setStatusCode(201);
            } else {
                $this->setStatusCode(400);
            }
        
            return $this->respond($bool);
        } else {
            $this->setStatusCode(400);
            return $this->respond([]);
        }
    }
    
    public function getLichSuYLenh(Request $request)
    {
        $input = $request->all();
        if(!$input['dieu_tri_id'])
            $input['dieu_tri_id'] = $this->dieuTriService->getPhieuDieuTri($input);
            
        $data = $this->yLenhService->getLichSuYLenh($input);
        if(!$data) {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
}
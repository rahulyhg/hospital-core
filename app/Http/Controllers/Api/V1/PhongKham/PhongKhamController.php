<?php
namespace App\Http\Controllers\Api\V1\PhongKham;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\HsbaKhoaPhongService;
use App\Services\SttPhongKhamService;
use App\Services\DieuTriService;
use App\Services\Icd10Service;

class PhongKhamController extends APIController
{
    public function __construct(HsbaKhoaPhongService $hsbaKhoaPhongService, SttPhongKhamService $sttPhongKhamService, DieuTriService $dieuTriService, Icd10Service $icd10Service)
    {
        $this->hsbaKhoaPhongService = $hsbaKhoaPhongService;
        $this->sttPhongKhamService = $sttPhongKhamService;
        $this->dieuTriService = $dieuTriService;
        $this->icd10Service = $icd10Service;
    }
    
    public function updateHsbaKhoaPhong($hsbaKhoaPhongId, Request $request)
    {
        try {
            $isNumeric = is_numeric($hsbaKhoaPhongId);
            
            if($isNumeric) {
                $input = $request->all();
                $this->hsbaKhoaPhongService->updateHsbaKhoaPhong($hsbaKhoaPhongId, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        }
    }
    
    public function getHsbaKhoaPhongById($hsbaKhoaPhongId)
    {
        $isNumeric = is_numeric($hsbaKhoaPhongId);
        
        if($isNumeric) {
            $data = $this->hsbaKhoaPhongService->getHsbaKhoaPhongById($hsbaKhoaPhongId);
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
        return $data;
    }
    
    public function chuyenKhoaPhong(Request $request)
    {   
        $input = $request->all();
        $data = $this->dieuTriService->createChuyenPhong($input);
        return $data;
        // try 
        // {
        //     $data = $this->benhNhanService->createChuyenPhong($request);
        //     $this->setStatusCode(201);
        //     return $this->respond($data);
        // } catch (\Exception $ex) {
        //     return $this->respondInternalError($ex->getMessage());
        //     return $ex;
        // }
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
}
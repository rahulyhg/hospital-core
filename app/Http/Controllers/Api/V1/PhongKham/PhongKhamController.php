<?php
namespace App\Http\Controllers\Api\V1\PhongKham;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\HsbaKhoaPhongService;
use App\Services\SttPhongKhamService;
use App\Services\DieuTriService;
use App\Services\Icd10Service;
use App\Services\YLenhService;
use App\Services\PhacDoDieuTriService;
use App\Services\PhieuYLenhService;

class PhongKhamController extends APIController
{
    public function __construct
    (
        HsbaKhoaPhongService $hsbaKhoaPhongService, 
        SttPhongKhamService $sttPhongKhamService, 
        DieuTriService $dieuTriService, 
        Icd10Service $icd10Service,
        YLenhService $yLenhService,
        PhacDoDieuTriService $pddtService,
        PhieuYLenhService $phieuYLenhService
    )
    {
        $this->hsbaKhoaPhongService = $hsbaKhoaPhongService;
        $this->sttPhongKhamService = $sttPhongKhamService;
        $this->dieuTriService = $dieuTriService;
        $this->icd10Service = $icd10Service;
        $this->yLenhService = $yLenhService;
        $this->pddtService = $pddtService;
        $this->phieuYLenhService = $phieuYLenhService;
    }
    
    public function update($hsbaKhoaPhongId, Request $request)
    {
        try {
            $isNumeric = is_numeric($hsbaKhoaPhongId);
            
            if($isNumeric) {
                $input = $request->all();
                
                $data = $this->hsbaKhoaPhongService->update($hsbaKhoaPhongId, $input);
                if($data['status'] === 'error') {
                    $this->setStatusCode($data['statusCode']);
                }
                return $this->respond($data);
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
    
    public function batDauKham($hsbaKhoaPhongId)
    {
        if(is_numeric($hsbaKhoaPhongId)) {
            $this->hsbaKhoaPhongService->batDauKham($hsbaKhoaPhongId);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    

    public function getYLenhByHsbaId($hsbaId)
    {
        if(is_numeric($hsbaId)) {
            $data = $this->yLenhService->getYLenhByHsbaId($hsbaId);
        }
        else 
        {
            $this->setStatusCode(400);
            $data = [];
        }
          return $this->respond($data);
      }

    public function getPddtByIcd10Code($icd10Code)
    {
        $data = $this->pddtService->getPddtByIcd10Code($icd10Code);
        
        if(!$data) {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getListPhieuYLenh($hsbaId)
    {
        $isNumeric = is_numeric($hsbaId);

        if($isNumeric) {
            $data = $this->phieuYLenhService->getListPhieuYLenh($hsbaId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }

    public function getDetailPhieuYLenh($phieuYLenhId,$type)
    {
        $isNumeric = is_numeric($phieuYLenhId);
        $typeIsNumeric = is_numeric($type);
        
        if($isNumeric && $typeIsNumeric) {
            $data = $this->yLenhService->getDetailPhieuYLenh($phieuYLenhId,$type);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }    
}
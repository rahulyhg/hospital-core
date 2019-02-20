<?php
namespace App\Http\Controllers\Api\V1\PhongKham;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\HsbaKhoaPhongService;
use App\Services\HsbaPhongKhamService;
use App\Services\SttPhongKhamService;
use App\Services\DieuTriService;
use App\Services\Icd10Service;
use App\Services\YLenhService;
use App\Services\PhacDoDieuTriService;
use App\Services\PhieuYLenhService;
use Validator;
use App\Http\Requests\UploadFileFormRequest;

class PhongKhamController extends APIController
{
    public function __construct
    (
        HsbaKhoaPhongService $hsbaKhoaPhongService, 
        HsbaPhongKhamService $hsbaPhongKhamService,
        SttPhongKhamService $sttPhongKhamService, 
        DieuTriService $dieuTriService, 
        Icd10Service $icd10Service,
        YLenhService $yLenhService,
        PhacDoDieuTriService $pddtService,
        PhieuYLenhService $phieuYLenhService
    )
    {
        $this->hsbaKhoaPhongService = $hsbaKhoaPhongService;
        $this->hsbaPhongKhamService = $hsbaPhongKhamService;
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
    
    public function updateInfoDieuTri(UploadFileFormRequest $request)
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
    
    public function getLichSuThuocVatTu(Request $request)
    {
        $input = $request->all();
        if(!$input['dieu_tri_id'])
            $input['dieu_tri_id'] = $this->dieuTriService->getPhieuDieuTri($input);
            
        $data = $this->yLenhService->getLichSuThuocVatTu($input);
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
    
    public function getPddtByIcd10Code($icd10Code)
    {
        $data = $this->pddtService->getPddtByIcd10Code($icd10Code);
        
        if(!$data) {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getListPhieuYLenh($hsbaId,$type)
    {
        $isNumeric = is_numeric($hsbaId);

        if($isNumeric) {
            $data = $this->phieuYLenhService->getListPhieuYLenh($hsbaId,$type);
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
    
    public function updateHsbaPhongKham($hsbaKhoaPhongId, UploadFileFormRequest $request)
    {
        try {
            $isNumeric = is_numeric($hsbaKhoaPhongId);
            
            if($isNumeric) {
                $input = $request->all();
                $data = $this->hsbaPhongKhamService->update($hsbaKhoaPhongId, $input);
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
    
    public function getDetailHsbaPhongKham($hsbaId, $phongId) {
        $isNumeric = is_numeric($hsbaId);
        $phongIsNumeric = is_numeric($phongId);
        
        if($isNumeric && $phongIsNumeric) {
            $data = $this->hsbaPhongKhamService->getDetailHsbaPhongKham($hsbaId, $phongId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
      
        return $this->respond($data);
    }
  
    public function countItemYLenh($hsbaId)
    {
        $isNumeric = is_numeric($hsbaId);
           
        if($isNumeric) {
            $data = $this->yLenhService->countItemYLenh($hsbaId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
            
        return $this->respond($data);
    }
    
    public function countItemThuocVatTu($hsbaId)
    {
        $isNumeric = is_numeric($hsbaId);
           
        if($isNumeric) {
            $data = $this->yLenhService->countItemThuocVatTu($hsbaId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
            
        return $this->respond($data);
    }
    
    public function searchIcd10Code($icd10Code)
    {
        if($icd10Code) {
            $data = $this->icd10Service->searchIcd10Code($icd10Code);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function searchIcd10Text($icd10Text)
    {
        if($icd10Text) {
            $data = $this->icd10Service->searchIcd10Text($icd10Text);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getListHsbaPhongKham($hsbaId)
    {
        $isNumeric = is_numeric($hsbaId);
        
        if($isNumeric) {
            $data = $this->hsbaPhongKhamService->getListHsbaPhongKham($hsbaId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getAllCanLamSang($hsbaId)
    {
        $isNumeric = is_numeric($hsbaId);

        if($isNumeric) {
            $data = $this->yLenhService->getAllCanLamSang($hsbaId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }    
}
<?php
namespace App\Http\Controllers\Api\V1\PhacDoDieuTri;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\PhacDoDieuTriService;
use App\Services\Icd10Service;

class PhacDoDieuTriController extends APIController
{
    public function __construct(PhacDoDieuTriService $pddtService, Icd10Service $icd10Service)
    {
        $this->pddtService = $pddtService;
        $this->icd10Service = $icd10Service;
    }
    
    public function getListIcd10(Request $request) 
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $keyword = $request->query('keyword', '');
    
        $data = $this->icd10Service->getListIcd10($limit, $page, $keyword);
        return $this->respond($data);
    }
    
    public function searchIcd10($keyword)
    {
        $data = $this->icd10Service->searchIcd10($keyword);
        return $this->respond($data);
    }
    
    public function createPddt(Request $request)
    {
        try {
            $input = $request->all();
            $this->pddtService->createPhacDoDieuTri($input);
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    
    public function getPddtByIcd10Id($icd10Id)
    {
        $isNumeric = is_numeric($icd10Id);
        
        if($isNumeric) {
            $data = $this->pddtService->getPddtByIcd10Id($icd10Id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getPddtById($pddtId)
    {
        $isNumeric = is_numeric($pddtId);
        
        if($isNumeric) {
            $data = $this->pddtService->getPddtById($pddtId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function updatePddt($pddtId, Request $request)
    {
        try {
            $input = $request->all();
            $this->pddtService->updatePhacDoDieuTri($pddtId, $input);
        } catch (\Exception $ex) {
            $this->setStatusCode(400);
            return $ex;
        }
    }
    
    // public function getListPhacDoDieuTri(Request $request)
    // {
    //     $limit = $request->query('limit', 100);
    //     $page = $request->query('page', 1);
    //     $keyword = $request->query('keyword', '');
        
    //     $data = $this->pddtService->getListPhacDoDieuTri($limit, $page, $keyword);
    //     return $this->respond($data);
    // }
    
    // public function getPddtByCode($icd10Code)
    // {
    //     if($icd10Code) {
    //         $data = $this->pddtService->getPddtByCode($icd10Code);
    //     } else {
    //         $this->setStatusCode(400);
    //         $data = [];
    //     }
        
    //     return $this->respond($data);
    // }
    
    public function saveYLenhGiaiTrinh(Request $request)
    {
        try {
            $input = $request->all();
            $this->pddtService->saveYLenhGiaiTrinh($input);
        } catch (\Exception $ex) {
            $this->setStatusCode(400);
            return $ex;
        }
    }
    
    public function confirmGiaiTrinh(Request $request)
    {
        try {
            $input = $request->all();
            $this->pddtService->confirmGiaiTrinh($input);
        } catch (\Exception $ex) {
            $this->setStatusCode(400);
            return $ex;
        }
    }
}
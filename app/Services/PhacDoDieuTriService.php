<?php
namespace App\Services;

use App\Http\Resources\PddtResource;
use App\Repositories\PhacDoDieuTriRepository;
use App\Repositories\DanhMuc\DanhMucDichVuRepository;
use App\Services\DanhMucThuocVatTuService;

class PhacDoDieuTriService
{
    public function __construct
    (
        PhacDoDieuTriRepository $pddtRepository, 
        DanhMucDichVuRepository $dmdvRepository,
        DanhMucThuocVatTuService $danhMucThuocVatTuService
    )
    {
        $this->pddtRepository = $pddtRepository;
        $this->dmdvRepository = $dmdvRepository;
        $this->danhMucThuocVatTuService = $danhMucThuocVatTuService;
    }
    
    public function createPhacDoDieuTri(array $input)
    {
        $this->pddtRepository->createPhacDoDieuTri($input);
    }
    
    public function getPddtByIcd10Id($icd10Id)
    {
        $result = $this->pddtRepository->getPddtByIcd10Id($icd10Id);
        $data = [];
        
        if($result['listIdCls']) {
            $data['yLenh'] = $this->dmdvRepository->getYLenhByListId($result['listIdCls']);
            $data['list'] = $result['list'];
        }
        
        if($result['listIdTvt']) {
            $data['thuocVatTu'] = $this->danhMucThuocVatTuService->searchThuocVatTuByListId($result['listIdTvt']);
            $data['list'] = $result['list'];
        }
        
        return $data;
    }
    
    public function getPddtById($pddtId)
    {
        $result = $this->pddtRepository->getPddtById($pddtId);
        if($result['listId']) {
            if($result['obj']->loai_nhom == 1) {
                $data['yLenh'] = $this->dmdvRepository->getYLenhByListId($result['listId']);
                $data['thuocVatTu'] = [];
            } else {
                $data['yLenh'] = [];
                $data['thuocVatTu'] = $this->danhMucThuocVatTuService->searchThuocVatTuByListId($result['listId']);
            }
            
            $data['obj'] = $result['obj'];
            return $data;
        } else {
            return [];
        }
    }
    
    public function updatePhacDoDieuTri($pddtId, array $input)
    {
        $this->pddtRepository->updatePhacDoDieuTri($pddtId, $input);
    }
    
    public function getPddtByIcd10Code($icd10Code)
    {
        $result = $this->pddtRepository->getPddtByIcd10Code($icd10Code);
        $data = [];
        
        if($result) {
            if($result['listIdCls'])
                $data['yLenh'] = $this->dmdvRepository->getYLenhByListId($result['listIdCls']);
            else 
                $data['yLenh'] = [];
                
            if($result['listIdTvt']) 
                $data['thuocVatTu'] = $this->danhMucThuocVatTuService->searchThuocVatTuByListId($result['listIdTvt']);
            else
                $data['thuocVatTu'] = [];
                
            $data['list'] = $result['list'];
        }
        
        return $data;
    }
    
    // public function getListPhacDoDieuTri($limit, $page, $keyword)
    // {
    //     $data = $this->pddtRepository->getListPhacDoDieuTri($limit, $page, $keyword);
        
    //     return $data;
    // }
    
    // public function getPddtByCode($icd10Code)
    // {
    //     $data = $this->pddtRepository->getDataPddtByCode($icd10Code);
        
    //     return new PddtResource($data);
    // }
    
    public function saveYLenhGiaiTrinh(array $input)
    {
        $this->pddtRepository->saveYLenhGiaiTrinh($input);
    }
    
    public function confirmGiaiTrinh(array $input)
    {
        $this->pddtRepository->confirmGiaiTrinh($input);
    }
}
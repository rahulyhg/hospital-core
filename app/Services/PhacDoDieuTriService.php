<?php
namespace App\Services;

use App\Http\Resources\PddtResource;
use App\Repositories\PhacDoDieuTriRepository;
use App\Repositories\DanhMuc\DanhMucDichVuRepository;

class PhacDoDieuTriService
{
    public function __construct(PhacDoDieuTriRepository $pddtRepository, DanhMucDichVuRepository $dmdvRepository)
    {
        $this->pddtRepository = $pddtRepository;
        $this->dmdvRepository = $dmdvRepository;
    }
    
    public function createPhacDoDieuTri(array $input)
    {
        $this->pddtRepository->createPhacDoDieuTri($input);
    }
    
    public function getPddtByIcd10Id($icd10Id)
    {
        $result = $this->pddtRepository->getPddtByIcd10Id($icd10Id);
        if($result['listId']) {
            $data['yLenh'] = $this->dmdvRepository->getYLenhByListId($result['listId']);
            $data['pddt'] = $result['data'];
            return $data;
        } else {
            return [];
        }
    }
    
    public function getPddtById($pddtId)
    {
        $result = $this->pddtRepository->getPddtById($pddtId);
        if($result['listId']) {
            $data['yLenh'] = $this->dmdvRepository->getYLenhByListId($result['listId']);
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
        if($result['listId']) {
            $data['yLenh'] = $this->dmdvRepository->getYLenhByListId($result['listId']);
            $data['list'] = $result['list'];
            return $data;
        } else {
            return [];
        }
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
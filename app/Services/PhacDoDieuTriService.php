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
    
    public function getListPhacDoDieuTri($limit, $page, $keyword)
    {
        $data = $this->pddtRepository->getListPhacDoDieuTri($limit, $page, $keyword);
        
        return $data;
    }
    
    public function getPddtById($pddtId)
    {
        $result = $this->pddtRepository->getDataPhacDoDieuTriById($pddtId);
        if($result['listId']) {
            $data['pddt'] = $this->dmdvRepository->getYLenhByListId($result['listId']);
            $data['giaiTrinh'] = $result['giaiTrinh']; 
            return $data;
        } else {
            return [];
        }
    }
    
    public function savePhacDoDieuTri($pddtId, array $input)
    {
        $this->pddtRepository->savePhacDoDieuTri($pddtId, $input);
    }
    
    public function getPddtByCode($icd10Code)
    {
        $data = $this->pddtRepository->getDataPddtByCode($icd10Code);
        
        return new PddtResource($data);
    }
    
    public function getPddtByIcd10Code($icd10Code)
    {
        $result = $this->pddtRepository->getPddtByIcd10Code($icd10Code);
        if($result['listId']) {
            $data = $this->dmdvRepository->getYLenhByListId($result['listId']);
            $result['yLenh'] = $data;
        }
        return $result;
    }
    
    public function saveYLenhGiaiTrinh(array $input)
    {
        $this->pddtRepository->saveYLenhGiaiTrinh($input);
    }
    
    public function confirmGiaiTrinh(array $input)
    {
        $this->pddtRepository->confirmGiaiTrinh($input);
    }
}
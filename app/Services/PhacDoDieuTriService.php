<?php
namespace App\Services;

use App\Http\Resources\PddtResource;
use App\Repositories\PhacDoDieuTriRepository;

class PhacDoDieuTriService
{
    public function __construct(PhacDoDieuTriRepository $pddtRepository)
    {
        $this->pddtRepository = $pddtRepository;
    }
    
    public function getListPhacDoDieuTri($limit, $page, $keyword)
    {
        $data = $this->pddtRepository->getListPhacDoDieuTri($limit, $page, $keyword);
        
        return $data;
    }
    
    public function getPddtById($pddtId)
    {
        $data = $this->pddtRepository->getDataPhacDoDieuTriById($pddtId);
        
        return new PddtResource($data);
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
}
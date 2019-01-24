<?php

namespace App\Services;

use App\Models\Icd10;
use App\Http\Resources\Icd10Resource;
use App\Repositories\Icd10Repository;
use Illuminate\Http\Request;
use Validator;

class Icd10Service {
    public function __construct(Icd10Repository $icd10Repository)
    {
        $this->icd10Repository = $icd10Repository;
    }

    public function getIcd10ByCode($icd10Code)
    {
        $data = $this->icd10Repository->getIcd10ByCode($icd10Code);
        return $data;
    }
    
    public function getListIcd10ByCode($icd10Code)
    {
        $data = $this->icd10Repository->getListIcd10ByCode($icd10Code);
        return $data;
    }    
    
    public function searchIcd10Code($icd10Code)
    {
        $data = $this->icd10Repository->searchIcd10Code($icd10Code);
        return $data;
    }
    
    public function searchIcd10Text($icd10Text)
    {
        $data = $this->icd10Repository->searchIcd10Text($icd10Text);
        return $data;
    }
}
<?php
namespace App\Repositories;

use DB;
use App\Models\Icd10;
use App\Repositories\BaseRepositoryV2;

class Icd10Repository extends BaseRepositoryV2
{
    public function getModel()
    {
        return Icd10::class;
    }
    
    public function getIcd10ByCode($icd10code)
    {
        $column = [
            'icd10id',
            'icd10code',
            'icd10name'
        ];
        $data = $this->model->where('icd10code', '=', $icd10code)->get($column)->first();
        return $data;
    }
}
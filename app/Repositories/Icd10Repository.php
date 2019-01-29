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
    
    public function getListIcd10ByCode($icd10code)
    {
        $column = [
            'icd10id',
            'icd10code',
            'icd10name'
        ];
        $data = $this->model->where('icd10code', 'LIKE', '%'.$icd10code.'%')->get($column);
        return $data;
    } 
    
    public function searchIcd10Code($icd10Code)
    {
        $column = [
            'icd10id',
            'icd10code',
            'icd10name'
        ];
        
        $data = $this->model->where('icd10code', 'like', '%'.$icd10Code.'%')->orderBy('icd10id', 'asc')->get($column);
        return $data;
    }
    
    public function searchIcd10Text($keyword)
    {
        $column = [
            'icd10id',
            'icd10code',
            'icd10name'
        ];
        
        $query = $this->model;
        
        if($keyword != '') {
            $query = $query->where(function($queryAdv) use ($keyword) {
                $upperCase = mb_convert_case($keyword, MB_CASE_UPPER, "UTF-8");
                $lowerCase = mb_convert_case($keyword, MB_CASE_LOWER, "UTF-8");
                $titleCase = mb_convert_case($keyword, MB_CASE_TITLE, "UTF-8");
                $ucfirst = ucfirst($keyword);
                
                $queryAdv->where('icd10name', 'like', '%'.$upperCase.'%')
                        ->orWhere('icd10name', 'like', '%'.$lowerCase.'%')
                        ->orWhere('icd10name', 'like', '%'.$titleCase.'%')
                        ->orWhere('icd10name', 'like', '%'.$keyword.'%')
                        ->orWhere('icd10name', 'like', '%'.$ucfirst.'%');
            });
        }
        
        $data = $query->orderBy('icd10id', 'asc')->get($column);
        return $data;
    }
}
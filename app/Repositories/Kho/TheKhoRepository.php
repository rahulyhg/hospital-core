<?php
namespace App\Repositories\Kho;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\TheKho;

class TheKhoRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return TheKho::class;
    }  
    
    public function createTheKho(array $input)
    {
        $find = $this->model
                     ->where('danh_muc_thuoc_vat_tu_id',$input['danh_muc_thuoc_vat_tu_id'])
                     ->orderBy('ma_con','DESC')
                     ->first();
        if($find) {
            $explode = explode('.',$find['ma_con']);
            $input['ma_con']=$input['danh_muc_thuoc_vat_tu_id'].'.'.(intval($explode[1])+1);
        }
        else {
            $input['ma_con']=$input['danh_muc_thuoc_vat_tu_id'].'.1';
        }
        
        $id = $this->model->create($input)->id;
        return $id;
    }
}
<?php
namespace App\Repositories;

use DB;
use App\Models\MucHuong;
use App\Repositories\BaseRepositoryV2;

class MucHuongRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return MucHuong::class;
    }
    
    public function getListByHeSo($heSo)
    {
        $column = [
            'ma_doi_tuong',
            'he_so',
            'muc_huong_dung_tuyen'
        ];
        $data = $this->model->where('he_so', '=', $heSo)->get($column)->first();
        return $data;
    }  
    
    public function getListMucHuong()
    {
        $column = [
            'ma_doi_tuong',
            'he_so',
            'muc_huong_dung_tuyen'
        ];
        $data = $this->model->get($column);
        return $data;
    }    
}
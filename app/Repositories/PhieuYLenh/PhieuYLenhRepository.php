<?php
namespace App\Repositories\PhieuYLenh;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\PhieuYLenh;

class PhieuYLenhRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return PhieuYLenh::class;
    }
    
    public function getPhieuYLenhId(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function getPhieuYLenhId(array $input)
    {
        $where = [
            ['hsba_id', '=', $input['hsba_id']],
            ['benh_nhan_id', '=', $input['benh_nhan_id']],
            ['khoa_id', '=', $input['khoa_id']],
            ['phong_id', '=', $input['phong_id']],
            ['dieu_tri_id', '=', $input['dieu_tri_id']],
        ];
        
        $result = $this->model->where($where)->orderBy('id')->get()->first();
        
        if($result)
            return $result->id;
        else
            return $this->createDataPhieuYLenh($input);
    }
    
    public function getListPhieuYLenh($hsbaId)
    {
        $result = $this->model
                ->where('hsba_id',$hsbaId)
                ->orderBy('id')
                ->get();
        if($result)
            return $result;
        else
            return null;
    }    
}
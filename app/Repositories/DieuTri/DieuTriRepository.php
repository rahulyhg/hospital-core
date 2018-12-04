<?php
namespace App\Repositories\DieuTri;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\DieuTri;
use App\Http\Resources\HsbaResource;

class DieuTriRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return DieuTri::class;
    }
    
    public function createDataDieuTri(array $input)
    {
        $id = DieuTri::create($input)->id;
        return $id;
    }
    
    public function getDieuTriByHsba_Kp($hsba_khoa_phong_id, $khoa_id, $phong_id)
    {
        $where = [
                ['dieu_tri.hsba_khoa_phong_id', '=', $hsba_khoa_phong_id],
                ['dieu_tri.khoa_id', '=', $khoa_id],
                ['dieu_tri.phong_id', '=', $phong_id]
            ];
        $result = $this->model->where($where)->first(); 
        return $result;
    }
    
    public function updateDieuTri($dieu_tri_id, $input)
    {
        $dieuTri = DieuTri::findOrFail($dieu_tri_id);
		$dieuTri->update($input);
    }
    
    public function getPhieuDieuTri(array $input)
    {
        $where = [
            ['hsba_khoa_phong_id', '=', $input['hsba_khoa_phong_id']],    
            ['hsba_id', '=', $input['hsba_id']],
            ['benh_nhan_id', '=', $input['benh_nhan_id']],
            ['khoa_id', '=', $input['khoa_id']],
            ['phong_id', '=', $input['phong_id']],
        ];
        
        $result = $this->model->where($where)->orderBy('id')->get()->first();
        
        if($result)
            return $result;
        else
            return null;
    }
}
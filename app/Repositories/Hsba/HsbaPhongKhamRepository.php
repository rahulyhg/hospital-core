<?php
namespace App\Repositories\Hsba;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\HsbaPhongKham;
use Carbon\Carbon;

class HsbaPhongKhamRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return HsbaPhongKham::class;
    }
    
    public function createHsbaPhongKham(array $params) {
        $attributes = [
                        'ten_benh_nhan' => $params['ten_benh_nhan'],
                        'phong_id' => $params['phong_id'],
                        'khoa_id' => $params['khoa_id'],
                        'benh_vien_id' => $params['benh_vien_id'],
                        'hsba_id' => $params['hsba_id'],
                        'hsba_khoa_phong_id' => $params['hsba_khoa_phong_id'],
                        'auth_users_id' => null,
                    ];
                    
        $this->model->create($attributes);
    }
    
    public function updatePhongKham($hsbaKhoaPhongId, array $params)
    {
        $where = [
            ['hsba_khoa_phong_id', '=', $hsbaKhoaPhongId],
            ['phong_id', '=', $params['phong_id']]
        ];
        $model = $this->model->where($where);
		$model->update($params);
    }
    
    public function getByHSBAKPId($hsbaKhoaPhongId)
    {
        $where = [
            ['hsba_khoa_phong_id', '=', $hsbaKhoaPhongId],
        ];
        
        $result = $this->model->where($where)->get()->first();
        
        return $result;
    }
    
    public function getDetailHSBAPhongKham($hsbaId, $phongId) {
        $where = [
            ['hsba_id', '=', $hsbaId],
            ['phong_id', '=', $phongId]
        ];
        
        $result = $this->model->where($where)->get()->first();
        
        return $result;
    }
}
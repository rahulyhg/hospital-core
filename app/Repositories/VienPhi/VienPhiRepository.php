<?php
namespace App\Repositories\VienPhi;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\VienPhi;
use Carbon\Carbon;


class VienPhiRepository extends BaseRepositoryV2
{
    const VIEN_PHI_DONG = 0;
    const VIEN_PHI_MO = 1;
    
    public function getModel()
    {
        return VienPhi::class;
    }

    public function createDataVienPhi(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function updateVienPhi($vienPhiId, $params)
    {
        $vienPhi = $this->model->findOrFail($vienPhiId);
		$vienPhi->update($params);
    }
    
    public function getVienPhiById($vienPhiId)
    {
        $data = $this->model
                ->where('vien_phi.id', '=', $vienPhiId)
                ->first();
      return $data; 
    }
    
    public function getListVienPhiByHsbaId($hsbaId)
    {
        $data = $this->model
                ->where('hsba_id',$hsbaId)
                ->orderBy('thoi_gian_tao', 'desc')
                ->get();
        return $data; 
    }
    
    public function updateAndCreateVienPhi($vienPhiParams)
    {
        $status['trang_thai']=self::VIEN_PHI_DONG;
        $where=[
            ['hsba_id','=',$vienPhiParams['hsba_id']],
            ['trang_thai','=',self::VIEN_PHI_MO]
        ];
        $this->model->where($where)->update($status);
        
        $vienPhiParams['trang_thai']=self::VIEN_PHI_MO;
        $vienPhiParams['thoi_gian_tao']=Carbon::now()->toDateTimeString();
        $id = $this->model->create($vienPhiParams)->id;
        return $id;
    }
    
    public function getAllCanLamSang($hsbaId)
    {
        $column = [
            'y_lenh.*'
            ];
        $data = $this->model
                ->where('vien_phi.hsba_id',$hsbaId)
                ->whereIn('y_lenh.loai_y_lenh',[2,3,4])
                ->leftJoin('y_lenh','y_lenh.vien_phi_id','=','vien_phi.id')
                ->get($column);
        return $data; 
    }    
    
    
    
    // public function getInfoThanhToanVienPhi($hsbaId,$vienPhiId)
    // {
    //     $data = $this->model
    //             ->where('hsba_id',$hsbaId)
    //             ->orderBy('thoi_gian_tao', 'desc')
    //             ->get();
    //     return $data; 
    // }    
    
 
}
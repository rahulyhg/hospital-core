<?php
namespace App\Repositories\DanhMucDichVu;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\DanhMucDichVu;
use App\Http\Resources\HsbaResource;

class DanhMucDichVuRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return DanhMucDichVu::class;
    }
    
    public function createDataDanhMucDichVu(array $input)
    {
        $id = DanhMucDichVu::create($input)->id;
        return $id;
    }
    
    public function getDataDanhMucDichVuById($input)
    {
         $result = $this->model->where('danh_muc_dich_vu.id', $input)
                            ->first(); 
        return $result;
    }
}
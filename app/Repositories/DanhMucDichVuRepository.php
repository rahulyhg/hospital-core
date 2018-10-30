<?php
namespace App\Repositories;

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
    
    public function getDataYeuCauKham($request)
    {
        $data = DB::table('danh_muc_dich_vu')
                ->where('loai_nhom',$request->loai_nhom)
                ->orderBy('ten')
                ->get();
        return $data;    
    }
    
    public function createDataDanhMucDichVu(array $input)
    {
        $id = DanhMucDichVu::create($input)->id;
        return $id;
    }
    
    public function getDataDanhMucDichVuById($input)
    {
        $result = $this->model->where('danh_muc_dich_vu.id', $input)->first(); 
        return $result;
    }
    
    public function getListDanhMucDichVu($limit = 100, $page = 1)
    {
        $offset = ($page - 1) * $limit;
        
        $column = [
            'danh_muc_dich_vu.id',
            'ten_nhom',
            'dmth.gia_tri as loai_nhom_id',
            'dmth.dien_giai as loai_nhom',
            'ma',
            'ma_nhom_bhyt',
            'ten',
            'ten_nhan_dan',
            'ten_bhyt',
            'ten_nuoc_ngoai',
            'don_vi_tinh',
            'gia',
            'gia_nhan_dan',
            'gia_bhyt',
            'gia_nuoc_ngoai',
            'trang_thai',
            'nguoi_cap_nhat_id',
            'auth_users.fullname as nguoi_cap_nhat',
            'thoi_gian_cap_nhat'
        ];
        
        $query = DB::table('danh_muc_dich_vu')
            ->leftJoin('danh_muc_tong_hop as dmth', function($join) {
                $join->on(DB::raw('cast(dmth.gia_tri as integer)'), '=', 'danh_muc_dich_vu.loai_nhom')
                    ->where('dmth.khoa', '=', 'loai_nhom_dich_vu');
            })
            ->leftJoin('auth_users', 'auth_users.id', '=', 'danh_muc_dich_vu.nguoi_cap_nhat_id');
            
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->offset($offset)
                        ->limit($limit)
                        ->get($column);
        } else {
            $totalPage = 0;
            $data = [];
            $page = 0;
            $totalRecord = 0;
        }
            
        $result = [
            'data'          => $data,
            'page'          => $page,
            'totalPage'     => $totalPage,
            'totalRecord'   => $totalRecord
        ];
        
        return $result;
    }
    
}
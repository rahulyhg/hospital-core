<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\DanhMucDichVu;
use App\Http\Resources\HsbaResource;

class DanhMucDichVuRepository extends BaseRepositoryV2
{
    const SU_DUNG = 0;
    const LOAI_XET_NGHIEM = 2;
    const LOAI_CHAN_DOAN_HINH_ANH = 3;
    const LOAI_CHUYEN_KHOA = 4;
    const XET_NGHIEM = 'G1';
    const CHAN_DOAN_HINH_ANH = 'G2';
    const CHUYEN_KHOA = 'G3';
    
    public function getModel()
    {
        return DanhMucDichVu::class;
    }
    
    public function getDataYeuCauKham($input)
    {
        $data = DB::table('danh_muc_dich_vu')
                ->where('loai_nhom', $input['loai_nhom'])
                ->orderBy('ten')
                ->get();
        return $data;    
    }
    
    public function getDataDanhMucDichVuById($dmdvId)
    {
        $result = $this->model->where('danh_muc_dich_vu.id', $dmdvId)->first(); 
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
            
            $data = $query->orderBy('id', 'desc')
                        ->offset($offset)
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
    
    public function createDanhMucDichVu(array $input)
    {
        $id = DanhMucDichVu::create($input)->id;
        return $id;
    }
    
    public function updateDanhMucDichVu($dmdvId, array $input)
    {
        $dmdv = DanhMucDichVu::findOrFail($dmdvId);
		$dmdv->update($input);
    }
    
    public function deleteDanhMucDichVu($dmdvId)
    {
        DanhMucDichVu::destroy($dmdvId);
    }
    
    public function getYLenhByLoaiNhom($loaiNhom)
    {
        $column = [
            'danh_muc_dich_vu.id',
            'ten_nhom',
            'loai_nhom',
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
            'gia_nuoc_ngoai'
        ];
        
        $tenNhom = null;
        
        switch($loaiNhom) {
            case self::LOAI_XET_NGHIEM:
                $tenNhom = self::XET_NGHIEM;
                break;
            case self::LOAI_CHAN_DOAN_HINH_ANH:
                $tenNhom = self::CHAN_DOAN_HINH_ANH;
                break;
            case self::LOAI_CHUYEN_KHOA:
                $tenNhom = self::CHUYEN_KHOA;
                break;
        }
        
        $where = [
            ['loai_nhom', '=', $loaiNhom],    
            ['trang_thai', '=', self::SU_DUNG],
        ];
        
        $result = $this->model->where($where)->orderBy('id')->get($column);
        
        if($result) {
            list($parent, $children) = $result->partition(function($item) use($tenNhom) {
                return $item->ten_nhom == $tenNhom;
            });
            
            $data = $parent->each(function($itemParent, $keyParent) use ($children) {
                $arrayChildren = $children->filter(function($itemChildren, $keyChildren) use ($itemParent) {
                    if($itemChildren->ten_nhom == $itemParent->ma) {
                        $itemChildren['key'] = $itemChildren->id;
                        $itemChildren['parent'] = $itemParent->id;
                        return $itemChildren;
                    }
                })->values()->all();
                $itemParent['children'] = $arrayChildren;
                $itemParent['key'] = $itemParent->id;
                $itemParent['parent'] = 0;
            })->values()->all();
        }
        
        return $data;
    }
    
    
}
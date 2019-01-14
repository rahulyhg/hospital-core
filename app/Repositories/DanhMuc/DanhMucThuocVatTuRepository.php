<?php
namespace App\Repositories\DanhMuc;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\DanhMucThuocVatTu;
use App\Http\Resources\HsbaResource;
use Carbon\Carbon;

class DanhMucThuocVatTuRepository extends BaseRepositoryV2
{
    // const SU_DUNG = 0;
    const LOAI_QUAY_PHAT_THUOC = 2;
    const LOAI_VAT_TU_HAO_PHI = 4;
    // const LOAI_CHUYEN_KHOA = 4;
    // const XET_NGHIEM = 'G1';
    // const CHAN_DOAN_HINH_ANH = 'G2';
    // const CHUYEN_KHOA = 'G3';
    
    public function getModel()
    {
        return DanhMucThuocVatTu::class;
    }
    
    public function getThuocVatTuByLoaiNhom($loaiNhom)
    {
        $column = [
            'danh_muc_thuoc_vat_tu.id',
            'parent_id',
            'ma',
            'ma_nhom',
            'loai',
            'ten',
            'don_vi_tinh',
            'gia',
            'gia_bhyt',
            'gia_nuoc_ngoai',
            'the_kho.sl_kha_dung'
        ];
        
        $tenNhom = null;
        
        switch($loaiNhom) {
            case self::LOAI_QUAY_PHAT_THUOC:
                // $tenNhom = self::XET_NGHIEM;
                break;
            case self::LOAI_VAT_TU_HAO_PHI:
                // $tenNhom = self::CHAN_DOAN_HINH_ANH;
                break;
        }
        
        // $where = [
        //     ['loai_nhom', '=', $loaiNhom],    
        //     ['trang_thai', '=', self::SU_DUNG],
        // ];
        
        $result = $this->model->rightJoin('the_kho', function($join) use ($loaiNhom) {
                                   $join->on('the_kho.danh_muc_dich_vu_id', '=', 'danh_muc_thuoc_vat_tu.id');
                               })
                               ->where('the_kho.kho_id', '=', (int)$loaiNhom)
                               ->get($column);
        
        // if($result) {
        //     list($parent, $children) = $result->partition(function($item) use($tenNhom) {
        //         return $item->ten_nhom == $tenNhom;
        //     });
            
        //     $data = $parent->each(function($itemParent, $keyParent) use ($children) {
        //         $arrayChildren = $children->filter(function($itemChildren, $keyChildren) use ($itemParent) {
        //             if($itemChildren->ten_nhom == $itemParent->ma) {
        //                 $itemChildren['key'] = $itemChildren->id;
        //                 $itemChildren['parent'] = $itemParent->id;
        //                 $itemChildren['so_luong'] = 1;
        //                 return $itemChildren;
        //             }
        //         })->values()->all();
        //         $itemParent['children'] = $arrayChildren;
        //         $itemParent['key'] = $itemParent->id;
        //         $itemParent['parent'] = 0;
        //     })->values()->all();
        // } 
        
        return $result;
    }
    
    // public function getYLenhByListId($listId)
    // {
    //     $data = [];
        
    //     $result = $this->model->whereIn('id', $listId)->get();
    //     if($result) {
    //         foreach($result as $item) {
    //             $item['key'] = $item->id;
    //             $item['so_luong'] = 1;
    //             switch($item->loai_nhom) {
    //                 case self::LOAI_XET_NGHIEM:
    //                     $data['xet_nghiem'][] = $item;
    //                     break;
    //                 case self::LOAI_CHAN_DOAN_HINH_ANH:
    //                     $data['chan_doan_hinh_anh'][] = $item;
    //                     break;
    //                 case self::LOAI_CHUYEN_KHOA:
    //                     $data['chuyen_khoa'][] = $item;
    //                     break;
    //             }
    //         }
    //     }
        
    //     return $data;
    // }
}
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
    const MA_NHOM_CHA = ['0'];
    const MA_QUAY_PHAT_THUOC = ['TT', 'TNT'];
    const MA_VAT_TU_HAO_PHI = ['VT'];
    const MA_NHA_THUOC = ['NT'];
    const DANH_MUC_CHA = '0';
    const DANH_MUC_THUOC_BHYT = 'TT';
    const DANH_MUC_THUOC_THU_PHI = 'TNT';
    const DANH_MUC_VAT_TU_HAO_PHI = 'VT';
    const Y_LENH_THUOC = 5;
    const Y_LENH_VAT_TU = 6;
    
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
            // 'trang_thai'
        ];
        
        $ma = null;
        $type = null;
        
        switch($loaiNhom) {
            case self::LOAI_QUAY_PHAT_THUOC:
                $maNhom = array_merge(self::MA_QUAY_PHAT_THUOC, self::MA_NHOM_CHA);
                $ma = array_merge(self::MA_VAT_TU_HAO_PHI, self::MA_NHA_THUOC);
                $type = self::MA_QUAY_PHAT_THUOC;
                break;
            case self::LOAI_VAT_TU_HAO_PHI:
                $maNhom = array_merge(self::MA_VAT_TU_HAO_PHI, self::MA_NHOM_CHA);
                $ma = array_merge(self::MA_QUAY_PHAT_THUOC, self::MA_NHA_THUOC);
                $type = self::MA_VAT_TU_HAO_PHI;
                break;
        }
        
        $result = $this->model->whereRaw('id = parent_id')
                            ->whereIn('ma_nhom', $maNhom)
                            ->whereNotIn('ma', $ma)
                            ->orderBy('id')
                            ->get($column);
        
        $data = [];
        
        if($result) {
            list($root, $arrayData) = $result->partition(function($item) {
                return $item->ma_nhom == self::DANH_MUC_CHA;
            });
            
            if($loaiNhom == self::LOAI_QUAY_PHAT_THUOC) {
                list($thuocBhyt, $thuocThuPhi) = $arrayData->partition(function($item) {
                    return $item->ma_nhom == self::DANH_MUC_THUOC_BHYT;
                });
                
                $data = $root->each(function($item, $key) use ($thuocBhyt, $thuocThuPhi) {
                    if($item['ma'] == self::DANH_MUC_THUOC_BHYT) {
                        $item['children'] = $thuocBhyt->values()->all();
                    }
                    if($item['ma'] == self::DANH_MUC_THUOC_THU_PHI) {
                        $item['children'] = $thuocThuPhi->values()->all();
                    }
                })->values()->all();
            } else {
                $data = $root->each(function($item, $key) use ($arrayData) {
                    if($item['ma'] == self::DANH_MUC_VAT_TU_HAO_PHI) {
                        $item['children'] = $arrayData->values()->all();
                    }
                })->values()->all();
            }
        } 
        
        return $data;
    }
    
    public function getThuocVatTuByCode($maNhom, $loaiNhom)
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
        ];
        
        $result = $this->model->whereRaw('id = parent_id')
                            ->where('ma_nhom', $maNhom)
                            ->orderBy('ten')
                            ->get($column);
                            
        $result->each(function ($item, $key) use ($loaiNhom) {
            $item['so_luong'] = 1;
            $item['loai_nhom'] = ($loaiNhom == self::LOAI_QUAY_PHAT_THUOC) ? self::Y_LENH_THUOC : self::Y_LENH_VAT_TU;
        });
            
        return $result;
    }
    
    public function getAllThuocVatTu()
    {
        $where=[
            ['danh_muc_thuoc_vat_tu.trang_thai','=',1],
            ['hoat_chat.trang_thai','=',1],
            ];
        $column=[
            'danh_muc_thuoc_vat_tu.*',
            'hoat_chat.ten as hoat_chat',
            'don_vi_tinh.ten as don_vi_tinh',
            'gioi_han.kho_id'
            ];
        $result = $this->model
                    ->leftJoin('don_vi_tinh','don_vi_tinh.id','=','danh_muc_thuoc_vat_tu.don_vi_tinh_id')
                    ->leftJoin('hoat_chat','hoat_chat.id','=','danh_muc_thuoc_vat_tu.hoat_chat_id')
                    ->leftJoin('gioi_han','gioi_han.danh_muc_thuoc_vat_tu_id','=','danh_muc_thuoc_vat_tu.id')
                    ->where($where)
                    ->limit(1)
                    ->orderBy('id','ASC')
                    ->get($column);
                    
        return $result;
    }
    
    public function getThuocVatTu($lastId)
    {
        $where=[
            //['danh_muc_thuoc_vat_tu.trang_thai','=',1],
            ['danh_muc_thuoc_vat_tu.id','>',$lastId],
            //['hoat_chat.trang_thai','=',1]
            ];
        $column=[
            'danh_muc_thuoc_vat_tu.*',
            'hoat_chat.ten as hoat_chat',
            'don_vi_tinh.ten as don_vi_tinh',
            'don_vi_tinh.he_so_le_1',
            'don_vi_tinh.he_so_le_2',
            //'gioi_han.kho_id'
            ];
        $result = $this->model
                    ->leftJoin('don_vi_tinh','don_vi_tinh.id','=','danh_muc_thuoc_vat_tu.don_vi_tinh_id')
                    ->leftJoin('hoat_chat','hoat_chat.id','=','danh_muc_thuoc_vat_tu.hoat_chat_id')
                    //->leftJoin('gioi_han','gioi_han.danh_muc_thuoc_vat_tu_id','=','danh_muc_thuoc_vat_tu.id')
                    ->where($where)
                    ->orderBy('id','ASC')
                    ->limit(5000)
                    ->get($column);
                    
        return $result;
    } 
    
    public function getByListId(array $listId)
    {
        $where=[
            ['danh_muc_thuoc_vat_tu.trang_thai','=',1],
            ['hoat_chat.trang_thai','=',1]
            ];
        $column=[
            'danh_muc_thuoc_vat_tu.*',
            'hoat_chat.ten as hoat_chat',
            'don_vi_tinh.ten as don_vi_tinh',
            'gioi_han.kho_id'
            ];
        $result = $this->model
                    ->leftJoin('don_vi_tinh','don_vi_tinh.id','=','danh_muc_thuoc_vat_tu.don_vi_tinh_id')
                    ->leftJoin('hoat_chat','hoat_chat.id','=','danh_muc_thuoc_vat_tu.hoat_chat_id')
                    ->leftJoin('gioi_han','gioi_han.danh_muc_thuoc_vat_tu_id','=','danh_muc_thuoc_vat_tu.id')
                    ->where($where)
                    ->whereIn('danh_muc_thuoc_vat_tu.id',$listId)
                    ->get($column);
        return $result;
    }
}
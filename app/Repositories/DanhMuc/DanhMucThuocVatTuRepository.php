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
    
    public function getThuocVatTuByCode($maNhom)
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
        
        return $result;
    }
}
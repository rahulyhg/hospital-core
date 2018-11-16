<?php
namespace App\Repositories\HsbaKhoaPhong;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\HsbaKhoaPhong;
use Carbon\Carbon;

class HsbaKhoaPhongRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return HsbaKhoaPhong::class;
    }
    
    public function getListBenhNhan($phongId, $benhVienId, $startDay, $endDay, $limit = 20, $page = 1, $keyword = '', $status = -1)
    {
        $loaiBenhAn = 24; //kham benh
        $khoaHienTai = 3; //khoa kham benh
        $offset = ($page - 1) * $limit;
        
        if($phongId) {  //phong kham
            $where = [
                ['hsba_khoa_phong.loai_benh_an', '=', $loaiBenhAn],
                ['hsba_khoa_phong.phong_hien_tai', '=', $phongId],
                ['hsba_khoa_phong.benh_vien_id', '=', $benhVienId]
            ];
        } else {    //hanh chanh don tiep
            $where = [
                ['hsba_khoa_phong.loai_benh_an', '=', $loaiBenhAn],
                ['hsba_khoa_phong.khoa_hien_tai', '=', $khoaHienTai],
                ['hsba_khoa_phong.benh_vien_id', '=', $benhVienId]
            ];
        }
        
        $column = [
            'hsba.id as hsba_id',
            'hsba_khoa_phong.id as hsba_khoa_phong_id',
            'hsba.ten_benh_nhan',
            'hsba.nam_sinh',
            'hsba.ms_bhyt',
            'hsba.trang_thai_hsba',
            'hsba.ngay_tao',
            'hsba.ngay_ra_vien',
            'hsba_khoa_phong.thoi_gian_vao_vien',
            'hsba_khoa_phong.thoi_gian_ra_vien',
            'hsba_khoa_phong.id as hsba_khoa_phong_id',
            'hsba_khoa_phong.trang_thai_cls',
            'tt1.diengiai as ten_trang_thai_cls',
            'hsba_khoa_phong.trang_thai',
            'tt2.diengiai as ten_trang_thai',
            'hsba_khoa_phong.cdvv_icd10_code',
            'hsba_khoa_phong.cdvv_icd10_text',
            'hsba_khoa_phong.ly_do_vao_vien',
            'hsba_khoa_phong.qua_trinh_benh_ly',
            'hsba_khoa_phong.tien_su_benh_ban_than',
            'hsba_khoa_phong.tien_su_benh_gia_dinh'
        ];
        
        $query = DB::table('hsba_khoa_phong')
            ->leftJoin('hsba', 'hsba.id', '=', 'hsba_khoa_phong.hsba_id')
            ->leftJoin('red_trangthai as tt1', function($join) {
                $join->on('tt1.giatri', '=', 'hsba_khoa_phong.trang_thai_cls')
                    ->where('tt1.tablename', '=', 'canlamsang');
            })
            ->leftJoin('red_trangthai as tt2', function($join) {
                $join->on('tt2.giatri', '=', 'hsba_khoa_phong.trang_thai')
                    ->where('tt2.tablename', '=', 'patientstatus');
            });
            
        // if($phongId != 0) {
        //     $query = $query->leftJoin('stt_phong_kham as sttpk', function($join) use ($phongId) {
        //         $join->on('sttpk.hsba_id', '=', 'hsba_khoa_phong.hsba_id')
        //             ->where('sttpk.phong_id', '=', $phongId);
        //     });
            
        //     $arrayColumn = [
        //         'sttpk.kb_mach',
        //         'sttpk.kb_nhiet_do',
        //         'sttpk.kb_huyet_ap_thap',
        //         'sttpk.kb_huyet_ap_cao',
        //         'sttpk.kb_nhip_tho',
        //         'sttpk.kb_can_nang',
        //         'sttpk.kb_chieu_cao',
        //         'sttpk.kb_sp_o2'
        //     ];
            
        //     $column = array_merge($column, $arrayColumn);
        // }
            
        $query = $query->where($where);
        
        if($startDay == $endDay){
            $query = $query->whereDate('thoi_gian_vao_vien', '=', $startDay);
        } else {
            $query = $query->whereBetween('thoi_gian_vao_vien', [Carbon::parse($startDay)->startOfDay(), Carbon::parse($endDay)->endOfDay()]);
        }
        
        if($keyword != '') {
            $query = $query->where(function($queryAdv) use ($keyword) {
                $upperCase = mb_convert_case($keyword, MB_CASE_UPPER, "UTF-8");
                $lowerCase = mb_convert_case($keyword, MB_CASE_LOWER, "UTF-8");
                $titleCase = mb_convert_case($keyword, MB_CASE_TITLE, "UTF-8");
                
                $queryAdv->where('hsba.ten_benh_nhan', 'like', '%'.$upperCase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$lowerCase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$titleCase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$keyword.'%')
                        ->orWhere('hsba.ten_benh_nhan_khong_dau', 'like', '%'.$upperCase.'%')
                        ->orWhere('hsba.ten_benh_nhan_khong_dau', 'like', '%'.$lowerCase.'%')
                        ->orWhere('hsba.ten_benh_nhan_khong_dau', 'like', '%'.$titleCase.'%')
                        ->orWhere('hsba.ten_benh_nhan_khong_dau', 'like', '%'.$keyword.'%')
                        ->orWhereRaw("cast(hsba.id as text) like '%$keyword%'")
                        ->orWhereRaw("cast(hsba.ms_bhyt as text) like '%$keyword%'")
                        ->orWhereRaw("cast(hsba.ms_bhyt as text) like '%$upperCase%'");
            });
        }
        
        if($status != -1 && $phongId != 0) {
            $query = $query->where(function($queryAdv) use ($status) {
                $queryAdv->where('hsba_khoa_phong.trang_thai', '=', $status);
            });
        }
        
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->orderBy('thoi_gian_vao_vien', 'asc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get($column);
                        
            $data->each(function ($item, $key) {
                $item->hsba_id = sprintf('%012d', $item->hsba_id);
            });
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
    
    public function createDataHsbaKhoaPhong(array $input)
    {
         $id = HsbaKhoaPhong::create($input)->id;
         return $id;
    }
    
    public function updateHsbaKhoaPhong($hsbaKhoaPhongId,array $params)
    {
        $hsbaKhoaPhong = HsbaKhoaPhong::findOrFail($hsbaKhoaPhongId);
		$hsbaKhoaPhong->update($params);
    }
    
    public function getHsbaKhoaPhongById($hsbaKhoaPhongId)
    {
        $where = [
            ['hsba_khoa_phong.id', '=', $hsbaKhoaPhongId],
        ];
        
        $column = [
            'hsba_khoa_phong.id as hsba_khoa_phong_id',
            'hsba_khoa_phong.cdvv_icd10_code',
            'hsba_khoa_phong.cdvv_icd10_text',
            'hsba_khoa_phong.ly_do_vao_vien',
            'hsba_khoa_phong.qua_trinh_benh_ly',
            'hsba_khoa_phong.tien_su_benh_ban_than',
            'hsba_khoa_phong.tien_su_benh_gia_dinh',
            'hsba_khoa_phong.kb_toan_than',
            'hsba_khoa_phong.kb_bo_phan',
            'hsba_khoa_phong.kb_mach',
            'hsba_khoa_phong.kb_nhiet_do',
            'hsba_khoa_phong.kb_huyet_ap_thap',
            'hsba_khoa_phong.kb_huyet_ap_cao',
            'hsba_khoa_phong.kb_nhip_tho',
            'hsba_khoa_phong.kb_can_nang',
            'hsba_khoa_phong.kb_chieu_cao',
            'hsba_khoa_phong.kb_sp_o2',
            'hsba_khoa_phong.tom_tat_kq_cls',
            'hsba_khoa_phong.tom_tat_benh_an',
            'hsba_khoa_phong.cdvk_icd10_code',
            'hsba_khoa_phong.cdvk_icd10_text',
            'hsba_khoa_phong.tien_luong',
            'hsba_khoa_phong.huong_xu_tri',
            'hsba_khoa_phong.cdravien_icd10_code',
            'hsba_khoa_phong.cdravien_icd10_text',
            'hsba_khoa_phong.cdrv_kt_icd10_code',
            'hsba_khoa_phong.cdrv_kt_icd10_text'
        ];
        
        $result = $this->model->where($where)->get($column)->first();
        
        return $result;
    }

}
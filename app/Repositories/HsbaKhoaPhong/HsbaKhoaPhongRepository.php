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
    
    public function getListBenhNhanHanhChanh($benhVienId, $startDay, $endDay, $limit = 20, $page = 1, $keyword = '')
    {
        $loaiBenhAn = 24; //kham benh
        $khoaHienTai = 3; //khoa kham benh
        $offset = ($page - 1) * $limit;
        
        $where = [
            ['hsba_khoa_phong.loai_benh_an', '=', $loaiBenhAn],
            ['hsba_khoa_phong.khoa_hien_tai', '=', $khoaHienTai],
            ['hsba_khoa_phong.benh_vien_id', '=', $benhVienId]
        ];
        
        $column = [
            'hsba.id as hsba_id',
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
            })
            ->where($where);
        
        if($startDay == $endDay){
            $query = $query->whereDate('thoi_gian_vao_vien', '=', $startDay);
        } else {
            $query = $query->whereBetween('thoi_gian_vao_vien', [Carbon::parse($startDay)->startOfDay(), Carbon::parse($endDay)->endOfDay()]);
        }
        
        if($keyword != ''){
            $query = $query->where(function($queryAdv) use ($keyword) {
                $upperCase = mb_convert_case($keyword, MB_CASE_UPPER, "UTF-8");
                $lowerCase = mb_convert_case($keyword, MB_CASE_LOWER, "UTF-8");
                $titleCase = mb_convert_case($keyword, MB_CASE_TITLE, "UTF-8");
                
                $queryAdv->where('hsba.ten_benh_nhan', 'like', '%'.$upperCase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$lowerCase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$titleCase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$keyword.'%')
                        ->orWhere('hsba.ten_benh_nhan_khong_dau', 'like', '%'.$upperCase.'%')
                        ->orWhereRaw("cast(hsba.id as text) like '%$keyword%'")
                        ->orWhereRaw("cast(hsba.ms_bhyt as text) like '%$keyword%'")
                        ->orWhereRaw("cast(hsba.ms_bhyt as text) like '%$upperCase%'");
            });
        }
        
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->orderBy('thoi_gian_vao_vien', 'asc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get($column);
        } else {
            $totalPage = 0;
            $data = null;
            $page = 0;
        }
        
        $result = [
            'data'      => $data,
            'page'      => $page,
            'totalPage' => $totalPage
        ];
        
        return $result;
    }
    
    public function getListBenhNhanPhongKham($phongId, $benhVienId, $startDay, $endDay, $limit = 20, $page = 1, $keyword = '')
    {
        $loaiBenhAn = 24; //kham benh
        $offset = ($page - 1) * $limit;
        
        $where = [
            ['hsba_khoa_phong.loai_benh_an', '=', $loaiBenhAn],
            ['hsba_khoa_phong.phong_hien_tai', '=', $phongId],
            ['hsba_khoa_phong.benh_vien_id', '=', $benhVienId]
        ];
        
        $column = [
            'hsba.id as hsba_id',
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
        ];
        
        $query = DB::table('hsba_khoa_phong')
            ->join('hsba', 'hsba.id', '=', 'hsba_khoa_phong.hsba_id')
            ->leftJoin('red_trangthai as tt1', function($join) {
                $join->on('tt1.giatri', '=', 'hsba_khoa_phong.trang_thai_cls')
                    ->where('tt1.tablename', '=', 'canlamsang');
            })
            ->leftJoin('red_trangthai as tt2', function($join) {
                $join->on('tt2.giatri', '=', 'hsba_khoa_phong.trang_thai')
                    ->where('tt2.tablename', '=', 'patientstatus');
            })
            ->where($where);
        
        if($startDay == $endDay){
            $query = $query->whereDate('thoi_gian_vao_vien', '=', $startDay);
        } else {
            $query = $query->whereBetween('thoi_gian_vao_vien', [Carbon::parse($startDay)->startOfDay(), Carbon::parse($endDay)->endOfDay()]);
        }   
                
        if($keyword != ''){
            $query = $query->where(function($queryAdv) use ($keyword) {
                $upperCase = mb_convert_case($keyword, MB_CASE_UPPER, "UTF-8");
                $lowerCase = mb_convert_case($keyword, MB_CASE_LOWER, "UTF-8");
                $titleCase = mb_convert_case($keyword, MB_CASE_TITLE, "UTF-8");
                
                $queryAdv->where('hsba.ten_benh_nhan', 'like', '%'.$upperCase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$lowerCase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$titleCase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$keyword.'%')
                        ->orWhere('hsba.ten_benh_nhan_khong_dau', 'like', '%'.$upperCase.'%')
                        ->orWhereRaw("cast(hsba.id as text) like '%$keyword%'")
                        ->orWhereRaw("cast(hsba.ms_bhyt as text) like '%$keyword%'")
                        ->orWhereRaw("cast(hsba.ms_bhyt as text) like '%$upperCase%'");
            });
        }
        
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->orderBy('thoi_gian_vao_vien', 'asc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get($column);
        } else {
            $totalPage = 0;
            $data = null;
            $page = 0;
        }
        
        $result = [
            'data'      => $data,
            'page'      => $page,
            'totalPage' => $totalPage
        ];
        
        return $result;
    }
    
    public function createDataHsbaKhoaPhong(array $input)
    {
         $id = HsbaKhoaPhong::create($input)->id;
         return $id;
    }

}
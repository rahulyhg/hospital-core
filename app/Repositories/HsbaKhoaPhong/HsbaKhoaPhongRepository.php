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
    
    public function getListBN_HC($start_day, $end_day, $offset, $limit = 20, $keyword = '')
    {
        $loai_benh_an = 24; //kham benh
        $khoa_hien_tai = 3; //khoa kham benh
        
        $where = [
            ['hsba_khoa_phong.loai_benh_an', '=', $loai_benh_an],
            ['hsba_khoa_phong.khoa_hien_tai', '=', $khoa_hien_tai],
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
        
        if($start_day == $end_day){
            $query = $query->whereDate('thoi_gian_vao_vien', '=', $start_day);
        } else {
            $query = $query->whereBetween('thoi_gian_vao_vien', [Carbon::parse($start_day)->startOfDay(), Carbon::parse($end_day)->endOfDay()]);
        }
        
        if($keyword != ''){
            $query = $query->where(function($query_adv) use ($keyword) {
                $uppercase = mb_convert_case($keyword, MB_CASE_UPPER, "UTF-8");
                $lowercase = mb_convert_case($keyword, MB_CASE_LOWER, "UTF-8");
                $titlecase = mb_convert_case($keyword, MB_CASE_TITLE, "UTF-8");
                
                $query_adv->where('hsba.ten_benh_nhan', 'like', '%'.$uppercase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$lowercase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$titlecase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$keyword.'%')
                        ->orWhere('hsba.ten_benh_nhan_khong_dau', 'like', '%'.$uppercase.'%')
                        ->orWhere('hsba.id', 'like', '%'.$keyword.'%')
                        ->orWhere('hsba.ms_bhyt', 'like', '%'.$keyword.'%')
                        ->orWhere('hsba.ms_bhyt', 'like', '%'.$uppercase.'%');
            });
        }
        
        $data = $query->orderBy('thoi_gian_vao_vien', 'asc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get($column);
                        
        return $data;
    }
    
    public function getListBN_PK($phong_id, $start_day, $end_day, $offset, $limit = 20, $keyword = '')
    {
        $loai_benh_an = 24; //kham benh
        
        $where = [
            ['hsba_khoa_phong.loai_benh_an', '=', $loai_benh_an],
            ['hsba_khoa_phong.phong_hien_tai', '=', $phong_id],
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
        
        if($start_day == $end_day){
            $query = $query->whereDate('thoi_gian_vao_vien', '=', $start_day);
        } else {
            $query = $query->whereBetween('thoi_gian_vao_vien', [Carbon::parse($start_day)->startOfDay(), Carbon::parse($end_day)->endOfDay()]);
        }   
                
        if($keyword != ''){
            $query = $query->where(function($query_adv) use ($keyword) {
                $uppercase = mb_convert_case($keyword, MB_CASE_UPPER, "UTF-8");
                $lowercase = mb_convert_case($keyword, MB_CASE_LOWER, "UTF-8");
                $titlecase = mb_convert_case($keyword, MB_CASE_TITLE, "UTF-8");
                
                $query_adv->where('hsba.ten_benh_nhan', 'like', '%'.$uppercase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$lowercase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$titlecase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$keyword.'%')
                        ->orWhere('hsba.ten_benh_nhan_khong_dau', 'like', '%'.$uppercase.'%')
                        ->orWhere('hsba.id', 'like', '%'.$keyword.'%')
                        ->orWhere('hsba.ms_bhyt', 'like', '%'.$keyword.'%')
                        ->orWhere('hsba.ms_bhyt', 'like', '%'.$uppercase.'%');
            });
        }
        
        $data = $query->orderBy('thoi_gian_vao_vien', 'asc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get($column);
        
        return $data;
    }
    
    public function CreateDataHsbaKhoaPhong(array $input)
    {
         $id = HsbaKhoaPhong::create($input)->medicalrecordid;
         return $id;
    }

}
<?php
namespace App\Repositories\Hsba;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Hsba;
use App\Http\Resources\HsbaResource;

class HsbaRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return Hsba::class;
    }
    
    public function getHsbaByBenhNhanId($benh_nhan_id)
    {
        $column = [
            'hsba.id as hsba_id', 
            'hsba.ten_benh_nhan',
            'hsba.gioi_tinh',
            'hsba.ngay_sinh',
            'hsba.nam_sinh',
            'hsba.loai_benh_an',
            'hsba.trang_thai_hsba',
            'hsba.is_dang_ky_truoc',
            'hsba.phong_id',
            'department.departmentname as ten_phong',
        ];
        
        $result = $this->model->where('hsba.benh_nhan_id', $benh_nhan_id)
                            ->join('department', 'department.departmentid', '=', 'hsba.phong_id')
                            ->get($column)
                            ->first();
        
        return $result;
    }
    
    public function getHsbaByHsbaId($hsba_id, $phong_id)
    {
        $loai_benh_an = 24; //kham benh
        
        if($phong_id != 0)
            $where = [
                ['hsba_khoa_phong.departmentid', '=', $phong_id],
                ['hsba.id', '=', $hsba_id]
            ];
        else
            $where = [
                ['hsba_khoa_phong.loai_benh_an', '=', $loai_benh_an],
                ['hsba.id', '=', $hsba_id]
            ];
        
        $column = [
            'hsba.id as hsba_id',
            'hsba.benh_nhan_id',
            'tt1.diengiai as loai_benh_an',
            'hsba.so_luu_tru',
            'hsba.so_vao_vien',
            'vienphi.vienphicode',
            'departmentgroup.departmentgroupname',
            'department.departmentname',
            'hsba.ten_benh_nhan',
            'hsba.ngay_sinh',
            'hsba.nam_sinh',
            'hsba.gioi_tinh',
            'hsba.ten_nghe_nghiep',
            'hsba.ten_quoc_tich',
            'hsba.ten_dan_toc',
            'hsba.noi_lam_viec',
            'hsba.so_nha',
            'hsba.duong_thon',
            'hsba.ten_phuong_xa',
            'hsba.ten_quan_huyen',
            'hsba.ten_tinh_thanh_pho',
            'hsba.dien_thoai_benh_nhan',
            'hsba.email_benh_nhan',
            'hsba.url_hinh_anh',
            'hsba.loai_nguoi_than',
            'hsba.ten_nguoi_than',
            'hsba.dien_thoai_nguoi_than',
            'hsba.ms_bhyt',
            'bhyt.bhyt_loaiid',
            'bhyt.bhytfromdate',
            'bhyt.bhytutildate',
            'bhyt.macskcbbd',
            'bhyt.noisinhsong',
            'bhyt.du5nam6thangluongcoban',
            'tt2.diengiai as doi_tuong_benh_nhan',
            'hsba_khoa_phong.id as hsba_khoa_phong_id',
            'hsba_khoa_phong.chan_doan_tuyen_duoi',
            'hsba_khoa_phong.chan_doan_tuyen_duoi_code',
            'hsba_khoa_phong.noi_gioi_thieu_id',
            'hsba_khoa_phong.phong_hien_tai',
            'hsba_khoa_phong.thoi_gian_vao_vien',
            'hsba_khoa_phong.hinh_thuc_vao_vien_id',
            'hsba_khoa_phong.thoi_gian_ra_vien',
            'hsba_khoa_phong.cdrv_icd10_code',
            'hsba_khoa_phong.cdrv_icd10_text',
            'hsba_khoa_phong.ket_qua_dieu_tri',
            'hsba_khoa_phong.hinh_thuc_ra_vien'
        ];
        
        $data = DB::table('hsba')
                ->join('hsba_khoa_phong', 'hsba_khoa_phong.hsba_id', '=', 'hsba.id')
                ->join('red_trangthai as tt1', function($join) {
                    $join->on('tt1.giatri', '=', 'hsba_khoa_phong.loai_benh_an')
                        ->where('tt1.tablename', '=', 'loaibenhanid');
                })
                ->join('red_trangthai as tt2', function($join) {
                    $join->on('tt2.giatri', '=', 'hsba_khoa_phong.doi_tuong_benh_nhan')
                        ->where('tt2.tablename', '=', 'doituongbenhnhan');
                })
                ->join('departmentgroup', 'departmentgroup.departmentgroupid', '=', 'hsba_khoa_phong.khoa_hien_tai')
                ->join('department', 'department.departmentid', '=', 'hsba_khoa_phong.phong_hien_tai')
                ->join('bhyt', 'bhyt.bhytid', '=', 'hsba_khoa_phong.bhyt_id')
                ->join('vienphi', 'vienphi.hosobenhanid', '=', 'hsba.id')
                ->where($where)
                ->get($column);
          
        $array = json_decode($data, true);
        
        return collect($array)->first();
    }
  
    public function CreateDataHosobenhan(array $input)
    {
        $id = Hosobenhan::create($input)->hosobenhanid;
        return $id;
    }
}
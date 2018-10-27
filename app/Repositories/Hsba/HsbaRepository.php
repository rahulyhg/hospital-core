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
    
    public function getHsbaByBenhNhanId($benhNhanId)
    {
        $column = [
            'hsba.id as hsba_id',
            'hsba.ten_benh_nhan as ho_va_ten',
            'hsba.ten_benh_nhan',
            'hsba.gioi_tinh_id as gioi_tinh',
            'hsba.ngay_sinh',
            'hsba.nam_sinh',
            'hsba.nghe_nghiep_id',
            'hsba.dan_toc_id',
            'hsba.quoc_tich_id',
            'hsba.so_nha',
            'hsba.duong_thon',
            'hsba.phuong_xa_id',
            'hsba.quan_huyen_id',
            'hsba.tinh_thanh_pho_id',
            'hsba.noi_lam_viec',
            'hsba.dien_thoai_benh_nhan',
            'hsba.email_benh_nhan',
            'hsba.dia_chi_lien_he',
            'hsba.url_hinh_anh',
            'hsba.loai_nguoi_than',
            'hsba.ten_nguoi_than',
            'hsba.dien_thoai_nguoi_than',
            'hsba.loai_benh_an',
            'hsba.trang_thai_hsba',
            'hsba.is_dang_ky_truoc',
            'hsba.phong_id',
            'phong.ten_phong',
            'hsba.ms_bhyt',
            'bhyt.ma_cskcbbd',
            'bhyt.tu_ngay',
            'bhyt.den_ngay',
            'bhyt.ma_noi_song',
            'bhyt.du5nam6thangluongcoban',
            'bhyt.dtcbh_luyke6thang'
        ];
        
        $result = $this->model->where('hsba.benh_nhan_id', $benhNhanId)
                            ->leftJoin('phong', 'phong.id', '=', 'hsba.phong_id')
                            ->leftJoin('bhyt', 'bhyt.ms_bhyt', '=', 'hsba.ms_bhyt')
                            ->get($column)
                            ->first();
        
        return $result;
    }
    
    public function getHsbaByHsbaId($hsbaId, $phongId)
    {
        $loaiBenhAn = 24; //kham benh
        
        if($phongId != 0)
            $where = [
                ['hsba_khoa_phong.phong_hien_tai', '=', $phongId],
                ['hsba.id', '=', $hsbaId]
            ];
        else
            $where = [
                ['hsba_khoa_phong.loai_benh_an', '=', $loaiBenhAn],
                ['hsba.id', '=', $hsbaId]
            ];
        
        $column = [
            'hsba.id as hsba_id',
            'hsba.benh_nhan_id',
            'tt1.diengiai as loai_benh_an',
            'hsba.so_luu_tru',
            'hsba.so_vao_vien',
            //'vienphi.vienphicode',
            'khoa.ten_khoa',
            'phong.ten_phong',
            'hsba.ten_benh_nhan',
            'hsba.ngay_sinh',
            'hsba.nam_sinh',
            'hsba.gioi_tinh_id as gioi_tinh',
            'hsba.nghe_nghiep_id',
            'hsba.dan_toc_id',
            'hsba.quoc_tich_id',
            'hsba.so_nha',
            'hsba.duong_thon',
            'hsba.phuong_xa_id',
            'hsba.quan_huyen_id',
            'hsba.tinh_thanh_pho_id',
            'hsba.noi_lam_viec',
            'hsba.dien_thoai_benh_nhan',
            'hsba.email_benh_nhan',
            'hsba.dia_chi_lien_he',
            'hsba.url_hinh_anh',
            'hsba.loai_nguoi_than',
            'hsba.ten_nguoi_than',
            'hsba.dien_thoai_nguoi_than',
            'hsba.ms_bhyt',
            'bhyt.ma_cskcbbd',
            'bhyt.tu_ngay',
            'bhyt.den_ngay',
            'bhyt.ma_noi_song',
            'bhyt.du5nam6thangluongcoban',
            'bhyt.dtcbh_luyke6thang',
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
                ->leftJoin('hsba_khoa_phong', 'hsba_khoa_phong.hsba_id', '=', 'hsba.id')
                ->leftJoin('red_trangthai as tt1', function($join) {
                    $join->on('tt1.giatri', '=', 'hsba_khoa_phong.loai_benh_an')
                        ->where('tt1.tablename', '=', 'loaibenhanid');
                })
                ->leftJoin('red_trangthai as tt2', function($join) {
                    $join->on('tt2.giatri', '=', 'hsba_khoa_phong.doi_tuong_benh_nhan')
                        ->where('tt2.tablename', '=', 'doituongbenhnhan');
                })
                ->leftJoin('khoa', 'khoa.id', '=', 'hsba_khoa_phong.khoa_hien_tai')
                ->leftJoin('phong', 'phong.id', '=', 'hsba_khoa_phong.phong_hien_tai')
                ->leftJoin('bhyt', 'bhyt.id', '=', 'hsba_khoa_phong.bhyt_id')
                ->leftJoin('vien_phi', 'vien_phi.hsba_id', '=', 'hsba.id')
                ->where($where)
                ->get($column);
          
        $array = json_decode($data, true);
        
        return collect($array)->first();
    }
  
    public function createDataHsba(array $input)
    {
        $id = Hsba::create($input)->id;
        return $id;
    }
    
    public function updateHsba($hsbaId, $request)
    {
        $hsba = Hsba::findOrFail($hsbaId);
		$hsba->update($request->all());
    }
}
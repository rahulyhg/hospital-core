<?php
namespace App\Repositories\Hsba;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Hsba;
use App\Http\Resources\HsbaResource;
use App\Helper\Util;

class HsbaRepository extends BaseRepositoryV2
{
    const BENH_AN_KHAM_BENH = 24;
    
    public function getModel()
    {
        return Hsba::class;
    }
    
    public function getHsbaByBenhNhanId($benhNhanId)
    {
        $column = [
            'hsba.id as hsba_id',
            'hsba.benh_nhan_id',
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
            'hsba.ten_phuong_xa',
            'hsba.ten_quan_huyen',
            'hsba.ten_tinh_thanh_pho',
            'hsba.noi_lam_viec',
            'hsba.dien_thoai_benh_nhan',
            'hsba.email_benh_nhan',
            'hsba.dia_chi_lien_he',
            'hsba.url_hinh_anh',
            // 'hsba.loai_nguoi_than',
            // 'hsba.ten_nguoi_than',
            // 'hsba.dien_thoai_nguoi_than',
            'hsba.nguoi_than',
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
    
    public function getHsbaByHsbaId($hsbaId)
    {
        $where = [
            ['hsba_khoa_phong.loai_benh_an', '=', self::BENH_AN_KHAM_BENH],
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
            'hsba.ten_phuong_xa',
            'hsba.ten_quan_huyen',
            'hsba.ten_tinh_thanh_pho',
            'hsba.noi_lam_viec',
            'hsba.dien_thoai_benh_nhan',
            'hsba.email_benh_nhan',
            'hsba.dia_chi_lien_he',
            'hsba.url_hinh_anh',
            'hsba.loai_nguoi_than',
            'hsba.ten_nguoi_than',
            'hsba.dien_thoai_nguoi_than',
            'hsba.ms_bhyt',
            'hsba.thx_gplace_json',
            'bhyt.ma_cskcbbd',
            'bhyt.tu_ngay',
            'bhyt.den_ngay',
            'bhyt.ma_noi_song',
            'bhyt.du5nam6thangluongcoban',
            'bhyt.dtcbh_luyke6thang',
            'tt2.diengiai as doi_tuong_benh_nhan',
            'hsba_khoa_phong.trang_thai',
            'hsba_khoa_phong.khoa_hien_tai',
            'hsba_khoa_phong.id as hsba_khoa_phong_id',
            'hsba_khoa_phong.cdvv_icd10_text',
            'hsba_khoa_phong.cdvv_icd10_code',
            'hsba_khoa_phong.ly_do_vao_vien',
            'hsba_khoa_phong.qua_trinh_benh_ly',
            'hsba_khoa_phong.tien_su_benh_ban_than',
            'hsba_khoa_phong.tien_su_benh_gia_dinh',
            'hsba_khoa_phong.cdtd_icd10_text',
            'hsba_khoa_phong.cdtd_icd10_code',
            'hsba_khoa_phong.noi_gioi_thieu_id',
            'hsba_khoa_phong.phong_hien_tai',
            'hsba_khoa_phong.thoi_gian_vao_vien',
            'hsba_khoa_phong.hinh_thuc_vao_vien_id',
            'hsba_khoa_phong.thoi_gian_ra_vien',
            'hsba_khoa_phong.cdrv_icd10_code',
            'hsba_khoa_phong.cdrv_icd10_text',
            'hsba_khoa_phong.cdrv_kt_icd10_code',
            'hsba_khoa_phong.cdrv_kt_icd10_text',
            'hsba_khoa_phong.ket_qua_dieu_tri',
            'hsba_khoa_phong.hinh_thuc_ra_vien',
            'hsba_khoa_phong.kham_toan_than',
            'hsba_khoa_phong.kham_bo_phan',
            'hsba_khoa_phong.ket_qua_can_lam_san',
            'hsba_khoa_phong.huong_xu_ly',
            'hsba_khoa_phong.tom_tat_benh_an',
            'hsba_khoa_phong.tien_luong',
            'hsba_khoa_phong.mach',
            'hsba_khoa_phong.nhiet_do',
            'hsba_khoa_phong.nhip_tho',
            'hsba_khoa_phong.sp_o2',
            'hsba_khoa_phong.can_nang',
            'hsba_khoa_phong.chieu_cao',
            'hsba_khoa_phong.thi_luc_mat_trai',
            'hsba_khoa_phong.thi_luc_mat_phai',
            'hsba_khoa_phong.kl_thi_luc_mat_trai',
            'hsba_khoa_phong.kl_thi_luc_mat_phai',
            'hsba_khoa_phong.nhan_ap_mat_trai',
            'hsba_khoa_phong.nhan_ap_mat_phai',
            'hsba_khoa_phong.huyet_ap_thap',
            'hsba_khoa_phong.huyet_ap_cao',
            'hsba_khoa_phong.chan_doan_ban_dau',
            'vien_phi.loai_vien_phi',
            'vien_phi.id as vien_phi_id',
            'bhyt.tuyen_bhyt',
            'sttpk.loai_stt',
            'sttpk.stt_don_tiep_id',
        ];
        
        $query = $this->model
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
                ->leftJoin('stt_phong_kham as sttpk', function($join) use ($hsbaId) {
                    $join->on('sttpk.hsba_id', '=', 'hsba_khoa_phong.hsba_id')
                        ->where('sttpk.hsba_id', '=', $hsbaId)
                        ->orderBy('sttpk.id', 'desc');
                });
            
        $data = $query->where($where)->get($column);
          
        $array = json_decode($data, true);
        
        return collect($array)->first();
    }
  
    public function createDataHsba(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function updateHsba($hsbaId, $input)
    {
        $thxData = isset($input['thx_gplace_json']) ? $input['thx_gplace_json'] : null;
        //$input['thx_gplace_json'] = $thxData ? json_encode($thxData) : null;
        // $input['ten_phuong_xa'] = null;
        // $input['ten_quan_huyen'] = null;
        // $input['ten_tinh_thanh_pho'] = null;
        
        if($thxData) {
            $input['thx_gplace_json'] = json_encode($thxData);
            $data = Util::getDataFromGooglePlace($thxData);
            $input['ten_phuong_xa'] = $data['ten_phuong_xa'];
            $input['ten_quan_huyen'] = $data['ten_quan_huyen'];
            $input['ten_tinh_thanh_pho'] = $data['ten_tinh_thanh_pho'];
        }
        
        $hsba = $this->model->findOrFail($hsbaId);
		$hsba->update($input);
    }
    
    
}
<?php
namespace App\Models\Sqs;

class DangKyKhamBenh extends BaseModel
{
    public $attributes = ['benh_vien_id', 'khoa_id', 'phong_id'];
    public $skipCheckFields = true;
    // public $fields = [
    //     'benh_vien_id', 'auth_users_id',
    //     'dan_toc_id', 'den_ngay', 'dia_chi_lien_he', 'dien_thoai_benh_nhan', 'dien_thoai_nguoi_than',
    //     'doi_tuong_benh_nhan', 'duong_thon', 'email_benh_nhan', 'gioiTinh',
    //     'gioi_tinh_id', 'gtTheDenMoi', 'gtTheTuMoi', 'hoTen', 'ho_va_ten', 'huyen_key', 'image_url_bhyt',
    //     'keys', 'khoa_id', 'loai_nguoi_than', 'loai_stt', 'loai_vien_phi', 'maDKBDMoi', 'maTheMoi', 'ma_nhom',
    //     'ma_noi_song', 'ma_tiem_chung', 'ms_bhyt', 'muc_huong', 'nam_sinh', 'ngaySinh', 'ngay_sinh', 'nghe_nghiep_id',
    //     'noi_lam_viec', 'phong_id', 'phuong_xa_id', 'quan_huyen_id', 'quoc_tich_id', 'quyen_loi', 'scan', 'so_cmnd',
    //     'so_nha', 'stt_don_tiep_id', 'tenDKBDMoi', 'ten_nguoi_than', 'thx_key', 'thx_name', 'tim_theo_ma_nhom', 'tinh_key',
    //     'tinh_thanh_pho_id', 'tu_ngay', 'tuoi', 'tuyen_bhyt', 'xa_key', 'yeu_cau_kham_id'
    // ];
    
    public $message;    
    public $body;
    
    public $validations = [];
    
}

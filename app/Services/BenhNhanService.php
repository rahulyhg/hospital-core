<?php

namespace App\Services;

use Illuminate\Http\Request;
use DB;
use App\Repositories\BenhNhan\BenhNhanRepository;
use App\Repositories\Hsba\HsbaRepository;
use App\Repositories\HsbaKhoaPhong\HsbaKhoaPhongRepository; 
use Validator;

class BenhNhanService{
    public function __construct(BenhNhanRepository $BenhNhanRepository, HsbaRepository $HsbaRepository, HsbaKhoaPhongRepository $HsbaKhoaPhongRepository)
    {
        $this->BenhNhanRepository = $BenhNhanRepository;
        $this->HsbaRepository = $HsbaRepository;
        $this->HsbaKhoaPhongRepository = $HsbaKhoaPhongRepository;
    }
    
    public function createBenhNhan(Request $request)
    {
        $array = $request->all();
        $benhNhanParams = $request->only('ho_va_ten', 'ngay_sinh', 'gioi_tinh', 'nghe_nghiep_id', 'dan_toc_id', 'quoc_tich_id'
                                        , 'so_nha', 'duong_thon', 'phuong_xa_id', 'quan_huyen_id','tinh_thanh_pho_id', 'noi_lam_viec'
                                        , 'loai_nguoi_than', 'ten_nguoi_than', 'dien_thoai_nguoi_than'
                                        , 'url_hinh_anh', 'dien_thoai_benh_nhan', 'email_benh_nhan', 'dia_chi_lien_he'
                                        );
        
        $hsbaParams = $request->only( 'auth_users_id', 'khoa_id', 'phong_id'
                                        , 'ngay_sinh', 'gioi_tinh', 'nghe_nghiep_id', 'dan_toc_id', 'quoc_tich_id'
                                        , 'so_nha', 'duong_thon', 'phuong_xa_id', 'quan_huyen_id','tinh_thanh_pho_id', 'noi_lam_viec'
                                        , 'loai_nguoi_than', 'ten_nguoi_than', 'dien_thoai_nguoi_than'
                                        , 'url_hinh_anh', 'dien_thoai_benh_nhan', 'email_benh_nhan', 'dia_chi_lien_he'
                                        //,'ms_bhyt' chưa xử lý BHYT
         );
         
         $hsba_kpParams = $request -> only ('auth_users_id', 'doi_tuong_benh_nhan', 'yeu_cau_kham_id', 'chan_doan_tuyen_duoi', 'chan_doan_tuyen_duoi_code'
                                     //,'bhyt_id', vienphiid chưa xử lý BHYT, vien phi
         );
         
         
        //validate data
        $result = DB::transaction(function () use ($benhNhanParams, $hsbaParams, $hsba_kpParams) {
            try {
                //insert tbl benh_nhan
                $id_benh_nhan = $this->BenhNhanRepository->createDataBenhNhan($benhNhanParams);
                //insert tbl hsba
                $hsbaParams['loai_benh_an'] = 24;
                $hsbaParams['hinh_thuc_vao_vien'] = 2;
                $hsbaParams['trang_thai_hsba'] = 0;
                $hsbaParams['benh_nhan_id'] = $id_benh_nhan;
                $hsbaParams['ten_benh_nhan'] = $benhNhanParams['ho_va_ten'];
                $hsbaParams['ten_benh_nhan_khong_dau'] = $this->convert_vi_to_en($benhNhanParams['ho_va_ten']);
                $hsbaParams['is_dang_ky_truoc'] = 0;
                $id_hsba = $this->HsbaRepository->createDataHsba($hsbaParams);
                $hsba_kpParams['khoa_hien_tai'] = $hsbaParams['khoa_id'];
                $hsba_kpParams['phong_hien_tai'] = $hsbaParams['phong_id'];;
                $hsba_kpParams['hsba_id'] = $id_hsba;
                $hsba_kpParams['trang_thai'] = 0;
                $hsba_kpParams['loai_benh_an'] = 24;
                $hsba_kpParams['benh_nhan_id'] = $id_benh_nhan;
                $hsba_kpParams['hinh_thuc_vao_vien_id'] = 2;
                $id_hsba_kp = $this->HsbaKhoaPhongRepository->createDataHsbaKhoaPhong($hsba_kpParams);
                return $id_hsba_kp;
                
            } catch (\Exception $ex) {
                  return $ex;
            }
            
            
        });
        
        return $result;
       
       
       
       
       
       
    }
    
    function convert_vi_to_en($str) {
      $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
      $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
      $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
      $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
      $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
      $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
      $str = preg_replace("/(đ)/", "d", $str);
      $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "A", $str);
      $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "E", $str);
      $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "I", $str);
      $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "O", $str);
      $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "U", $str);
      $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "Y", $str);
      $str = preg_replace("/(Đ)/", "D", $str);
      //$str = str_replace(” “, “-”, str_replace(“&*#39;”,”",$str));
      return $str;
    }
   
}
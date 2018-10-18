<?php

namespace App\Services;

use Illuminate\Http\Request;
use DB;
use App\Repositories\BenhNhan\BenhNhanRepository;
use App\Repositories\Hsba\HsbaRepository;
use App\Repositories\HsbaKhoaPhong\HsbaKhoaPhongRepository; 
use App\Repositories\Bhyt\BhytRepository; 
use App\Repositories\DanhMucTongHopRepository;
use Validator;

class BenhNhanService{
    public function __construct(BenhNhanRepository $benhNhanRepository, HsbaRepository $hsbaRepository, HsbaKhoaPhongRepository $hsbaKhoaPhongRepository, DanhMucTongHopRepository $danhMucTongHopRepository, BhytRepository $bhytRepository)
    {
        $this->BenhNhanRepository = $benhNhanRepository;
        $this->HsbaRepository = $hsbaRepository;
        $this->HsbaKhoaPhongRepository = $hsbaKhoaPhongRepository;
        $this->DanhMucTongHopRepository = $danhMucTongHopRepository;
        $this->BhytRepository = $bhytRepository;
    }
    
    public function createBenhNhan(Request $request)
    {
        //kiểm tra thông tin scan
        $scan = $request->only('scan');
        //return $idBenhNhan;
        //$array = $request->all();
        $dataNgheNghiep = $this -> DanhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('nghe_nghiep', $request['nghe_nghiep_id']);
        $dataDanToc =  $this -> DanhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('dan_toc', $request['dan_toc_id']);
        $dataQuocTich =  $this -> DanhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('quoc_tich', $request['quoc_tich_id']);
        $dataTinh = $this->DanhMucTongHopRepository->getDataTinh($request['tinh_thanh_pho_id']);
        $dataHuyen = $this->DanhMucTongHopRepository->getDataHuyen($request['tinh_thanh_pho_id'], $request['quan_huyen_id']);
        $dataXa = $this->DanhMucTongHopRepository->getDataXa($request['tinh_thanh_pho_id'], $request['quan_huyen_id'], $request['phuong_xa_id']);
        
        //set params benh_nhan 
        $benhNhanParams = $request->only('benh_nhan_id' ,'ho_va_ten', 'ngay_sinh', 'gioi_tinh_id'
                                        , 'so_nha', 'duong_thon', 'noi_lam_viec'
                                        , 'loai_nguoi_than', 'ten_nguoi_than', 'dien_thoai_nguoi_than'
                                        , 'url_hinh_anh', 'dien_thoai_benh_nhan', 'email_benh_nhan', 'dia_chi_lien_he'
                                        );
        $hsbaParams = $request->only( 'auth_users_id', 'khoa_id', 'phong_id'
                                                , 'ngay_sinh', 'gioi_tinh_id'
                                                , 'so_nha', 'duong_thon', 'noi_lam_viec'
                                                , 'loai_nguoi_than', 'ten_nguoi_than', 'dien_thoai_nguoi_than'
                                                , 'url_hinh_anh', 'dien_thoai_benh_nhan', 'email_benh_nhan', 'dia_chi_lien_he'
                                                , 'ms_bhyt'
                                        );
        $hsbaKpParams = $request -> only ('auth_users_id', 'doi_tuong_benh_nhan', 'yeu_cau_kham_id', 'chan_doan_tuyen_duoi', 'chan_doan_tuyen_duoi_code'
                                     //,'bhyt_id', vienphiid chưa xử lý BHYT, vien phi
                                        );
        $bhytParams = $request -> only ('ma_cskcbbd', 'tu_ngay', 'den_ngay', 'ma_noi_song', 'du5nam6thangluongcoban', 'dtcbh_luyke6thang'
                                        );
        $bhytParams['image_url'] = $request->only('image_url_bhyt')['image_url_bhyt'];     
        
        $result = DB::transaction(function () use ($benhNhanParams, $hsbaParams,$hsbaKpParams, $dataNgheNghiep, $dataDanToc, $dataQuocTich, $dataTinh, $dataHuyen, $dataXa, $bhytParams ) {
            try {
               
                //lưu bhyt : thì xét có bhyt hay ko: 
                //1.nếu có lưu bhyt để lấy id lưu vào khoa phòng, 
                //2.nếu ko có insert bn bình thường 
                //lưu ý cập nhật ms_bhyt ở hsba
                // if(strlen($scan['scan']) == 12)//thẻ bn
                //     $idBenhNhan = $this->BenhNhanRepository->checkMaSoBenhNhan(trim($scan['scan']));
                // if(strlen($scan['scan']) == 15)//thẻ bhyt
                //     $idBenhNhan = $this->BhytRepository->checkMaSoBhyt(trim($scan['scan']));
                $id_bhyt = null;
                if($hsbaParams['ms_bhyt'] != null && $bhytParams['tu_ngay'] != null && $bhytParams['den_ngay'] != null)
                {
                    $id_bhyt = $this->BhytRepository->createDataBHYT($bhytParams);
                     
                }
                
               
                //set params benh_nhan
                $benhNhanParams['nghe_nghiep_id'] = $dataNgheNghiep['gia_tri'];
                $benhNhanParams['dan_toc_id'] = $dataDanToc['gia_tri'];
                $benhNhanParams['quoc_tich_id'] = $dataQuocTich['gia_tri'];
                $benhNhanParams['tinh_thanh_pho_id'] = $dataTinh['ma_tinh'];
                $benhNhanParams['quan_huyen_id'] = $dataHuyen['ma_huyen'];
                $benhNhanParams['phuong_xa_id'] = $dataXa['ma_xa'];
                $benhNhanParams['nam_sinh'] =  str_limit($benhNhanParams['ngay_sinh'], 4,'');
                $idBenhNhan = null;
                if($idBenhNhan == null)//insert tbl benh_nhan
                     $idBenhNhan = $this->BenhNhanRepository->createDataBenhNhan($benhNhanParams);
                else 
                    $idBenhNhan = $idBenhNhan['benh_nhan_id'];
                //set params hsba 
                $hsbaParams['loai_benh_an'] = 24;
                $hsbaParams['hinh_thuc_vao_vien'] = 2;
                $hsbaParams['trang_thai_hsba'] = 0;
                $hsbaParams['benh_nhan_id'] = $idBenhNhan;
                $hsbaParams['ten_benh_nhan'] = $benhNhanParams['ho_va_ten'];
                $hsbaParams['ten_benh_nhan_khong_dau'] = $this->convertViToEn($benhNhanParams['ho_va_ten']);
                $hsbaParams['is_dang_ky_truoc'] = 0;
                $hsbaParams['ten_nghe_nghiep'] = $dataNgheNghiep['dien_giai'];
                $hsbaParams['ten_dan_toc'] = $dataDanToc['dien_giai'];
                $hsbaParams['ten_quoc_tich'] = $dataQuocTich['dien_giai'];
                $hsbaParams['nghe_nghiep_id'] = $dataNgheNghiep['gia_tri'];
                $hsbaParams['dan_toc_id'] = $dataDanToc['gia_tri'];
                $hsbaParams['quoc_tich_id'] = $dataQuocTich['gia_tri'];
                $hsbaParams['ten_tinh_thanh_pho'] = $dataTinh['ten_tinh'];
                $hsbaParams['ten_quan_huyen'] = $dataHuyen['ten_huyen'];
                $hsbaParams['ten_phuong_xa'] = $dataXa['ten_xa'];
                $hsbaParams['tinh_thanh_pho_id'] = $dataTinh['ma_tinh'];
                $hsbaParams['quan_huyen_id'] = $dataHuyen['ma_huyen'];
                $hsbaParams['phuong_xa_id'] = $dataXa['ma_xa'];
                $hsbaParams['nam_sinh'] =  str_limit($benhNhanParams['ngay_sinh'], 4,'');
                 //insert hsba
                $idHsba = $this->HsbaRepository->createDataHsba($hsbaParams);
                
                
                
                //set params hsba_khoa_phong
                $hsbaKpParams['khoa_hien_tai'] = $hsbaParams['khoa_id'];
                $hsbaKpParams['phong_hien_tai'] = $hsbaParams['phong_id'];;
                $hsbaKpParams['hsba_id'] = $idHsba;
                $hsbaKpParams['trang_thai'] = 0;
                $hsbaKpParams['loai_benh_an'] = 24;
                $hsbaKpParams['benh_nhan_id'] = $idBenhNhan;
                $hsbaKpParams['hinh_thuc_vao_vien_id'] = 2;
                 //insert hsba_khoa_phong
                $idHsbaKp = $this->HsbaKhoaPhongRepository->createDataHsbaKhoaPhong($hsbaKpParams);
                return $idHsba;
            } catch (\Exception $ex) {
                 throw $ex;
            }
        });
        
        return $result;
    }
    
    function convertViToEn($str) {
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
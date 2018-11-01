<?php

namespace App\Services;

use Illuminate\Http\Request;
use DB;
use App\Repositories\BenhNhan\BenhNhanRepository;
use App\Repositories\Hsba\HsbaRepository;
use App\Repositories\HsbaKhoaPhong\HsbaKhoaPhongRepository; 
use App\Repositories\Bhyt\BhytRepository; 
use App\Repositories\VienPhi\VienPhiRepository; 
use App\Repositories\DanhMucTongHopRepository;
use App\Repositories\DieuTri\DieuTriRepository;
use App\Repositories\PhieuYLenh\PhieuYLenhRepository;
use App\Repositories\DanhMucDichVuRepository;
use App\Repositories\YLenh\YLenhRepository;

use Validator;

class BenhNhanService{
    public function __construct(BenhNhanRepository $benhNhanRepository, HsbaRepository $hsbaRepository, HsbaKhoaPhongRepository $hsbaKhoaPhongRepository, DanhMucTongHopRepository $danhMucTongHopRepository, BhytRepository $bhytRepository, VienPhiRepository $vienPhiRepository, DieuTriRepository $dieuTriRepository, PhieuYLenhRepository $phieuYLenhRepository, DanhMucDichVuRepository $danhMucDichVuRepository, YLenhRepository $yLenhRepository)
    {
        $this->benhNhanRepository = $benhNhanRepository;
        $this->hsbaRepository = $hsbaRepository;
        $this->hsbaKhoaPhongRepository = $hsbaKhoaPhongRepository;
        $this->danhMucTongHopRepository = $danhMucTongHopRepository;
        $this->bhytRepository = $bhytRepository;
        $this->vienPhiRepository = $vienPhiRepository;
        $this->dieuTriRepository = $dieuTriRepository;
        $this->phieuYLenhRepository = $phieuYLenhRepository;
        $this->danhMucDichVuRepository = $danhMucDichVuRepository;
        $this->yLenhRepository = $yLenhRepository;
    }
    
    public function createBenhNhan(Request $request)
    {
        //1. Kiểm tra thông tin bảo hiểm
        //2. Nếu có bảo hiểm thì bệnh nhân này đã tồn tại không tạo mới thông tin bệnh nhân
        //3. Nếu ko tìm thấy bảo hiểm tạo mới thông tin bệnh nhân
        //4. Tạo Hsba, hsba_khoa_phong, vien_phi, dieu_tri, phieu_y_lenh, y_lenh
        //kiểm tra thông tin scan
        $scan = $request->only('scan');
        //return $idBenhNhan;
        //$array = $request->all();
        $dataNgheNghiep = $this -> danhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('nghe_nghiep', $request['nghe_nghiep_id']);
        $dataDanToc =  $this -> danhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('dan_toc', $request['dan_toc_id']);
        $dataQuocTich =  $this -> danhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('quoc_tich', $request['quoc_tich_id']);
        $dataTinh = $this->danhMucTongHopRepository->getDataTinh($request['tinh_thanh_pho_id']);
        $dataHuyen = $this->danhMucTongHopRepository->getDataHuyen($request['tinh_thanh_pho_id'], $request['quan_huyen_id']);
        $dataXa = $this->danhMucTongHopRepository->getDataXa($request['tinh_thanh_pho_id'], $request['quan_huyen_id'], $request['phuong_xa_id']);
        
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
                                                , 'ms_bhyt', 'benh_vien_id'
                                        );
        $hsbaKpParams = $request -> only ('auth_users_id', 'doi_tuong_benh_nhan', 'yeu_cau_kham_id', 'chan_doan_tuyen_duoi', 'chan_doan_tuyen_duoi_code'
                                            ,'benh_vien_id'
                                        );
        $bhytParams = $request -> only ('ma_cskcbbd', 'tu_ngay', 'den_ngay', 'ma_noi_song', 'du5nam6thangluongcoban', 'dtcbh_luyke6thang'
                                        );
        $bhytParams['image_url'] = $request->only('image_url_bhyt')['image_url_bhyt'];     
        $dieuTriParams = $request ->only ('cd_icd10_code', 'cd_icd10_text');
        
        $result = DB::transaction(function () use ($scan, $benhNhanParams, $hsbaParams,$hsbaKpParams, $dataNgheNghiep, $dataDanToc, $dataQuocTich, $dataTinh, $dataHuyen, $dataXa, $bhytParams ) {
            try {
               
                // if(strlen($scan['scan']) == 12)//thẻ bn
                //     $idBenhNhan = $this->BenhNhanRepository->checkMaSoBenhNhan(trim($scan['scan']));
                $idBhyt = null;
                $idBenhNhan = null;
                if(strlen($scan['scan']) == 15)//thẻ bhyt
                     $idBenhNhan = $this->bhytRepository->checkMaSoBhyt(trim($scan['scan']));
                //nếu tìm thấy thẻ bhyt -> bn đã tồn tại
                //xét điều kiện lưu bhyt
                if($hsbaParams['ms_bhyt'] != null && $bhytParams['tu_ngay'] != null && $bhytParams['den_ngay'] != null)
                    $idBhyt = $this->bhytRepository->createDataBhyt($bhytParams);
                //set params benh_nhan
                $benhNhanParams['nghe_nghiep_id'] = $dataNgheNghiep['gia_tri'];
                $benhNhanParams['dan_toc_id'] = $dataDanToc['gia_tri'];
                $benhNhanParams['quoc_tich_id'] = $dataQuocTich['gia_tri'];
                $benhNhanParams['tinh_thanh_pho_id'] = $dataTinh['ma_tinh'];
                $benhNhanParams['quan_huyen_id'] = $dataHuyen['ma_huyen'];
                $benhNhanParams['phuong_xa_id'] = $dataXa['ma_xa'];
                $benhNhanParams['nam_sinh'] =  str_limit($benhNhanParams['ngay_sinh'], 4,'');
                if($idBenhNhan == null)//insert tbl benh_nhan
                     $idBenhNhan = $this->benhNhanRepository->createDataBenhNhan($benhNhanParams);
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
                $idHsba = $this->hsbaRepository->createDataHsba($hsbaParams);
                //set params vien_phi
                $vienPhiParams['loai_vien_phi'] = $hsbaKpParams['doi_tuong_benh_nhan'] == 1 ? 4 : 1;
                $vienPhiParams['trang_thai'] = 0;
                $vienPhiParams['khoa_id'] = $hsbaParams['khoa_id'];
                $vienPhiParams['phong_id'] = $hsbaParams['phong_id'];
                $vienPhiParams['doi_tuong_benh_nhan'] = $hsbaKpParams['doi_tuong_benh_nhan'];
                $vienPhiParams['bhyt_id'] = $idBhyt;
                $vienPhiParams['benh_nhan_id'] = $idBenhNhan;
                $vienPhiParams['hsba_id'] = $idHsba;
                $vienPhiParams['trang_thai_thanh_toan_bh'] = 0;
                //insert vien_phi
                $idVienPhi = $this->vienPhiRepository->createDataVienPhi($vienPhiParams);
                //set params hsba_khoa_phong
                $hsbaKpParams['khoa_hien_tai'] = $hsbaParams['khoa_id'];
                $hsbaKpParams['phong_hien_tai'] = $hsbaParams['phong_id'];;
                $hsbaKpParams['hsba_id'] = $idHsba;
                $hsbaKpParams['trang_thai'] = 0;
                $hsbaKpParams['loai_benh_an'] = 24;
                $hsbaKpParams['benh_nhan_id'] = $idBenhNhan;
                $hsbaKpParams['hinh_thuc_vao_vien_id'] = 2;
                $hsbaKpParams['vien_phi_id'] = $idVienPhi;
                $hsbaKpParams['bhyt_id'] = $idBhyt;
                //insert hsba_khoa_phong
                $idHsbaKp = $this->hsbaKhoaPhongRepository->createDataHsbaKhoaPhong($hsbaKpParams);
                //set params dieu_tri
                $dieuTriParams['hsba_khoa_phong_id'] = $idHsbaKp;
                $dieuTriParams['hsba_id'] = $hsbaParams['khoa_id'];
                $dieuTriParams['khoa_id'] = $hsbaParams['khoa_id'];
                $dieuTriParams['phong_id'] = $hsbaParams['phong_id'];
                $dieuTriParams['auth_users_id'] = $hsbaParams['auth_users_id'];
                $dieuTriParams['benh_nhan_id'] = $idBenhNhan;
                $dieuTriParams['ten_benh_nhan'] = $benhNhanParams['ho_va_ten'];
                $dieuTriParams['nam_sinh'] = str_limit($benhNhanParams['ngay_sinh'], 4,'');
                //insert dieu_tri
                $idDieuTri = $this->dieuTriRepository->createDataDieuTri($dieuTriParams);
                //set params phieu_y_lenh
                $phieuYLenhParams['benh_nhan_id'] = $idBenhNhan;
                $phieuYLenhParams['vien_phi_id'] = $idVienPhi;
                $phieuYLenhParams['hsba_id'] = $idHsba;
                $phieuYLenhParams['dieu_tri_id'] = $idDieuTri;
                $phieuYLenhParams['khoa_id'] = $hsbaParams['khoa_id'];
                $phieuYLenhParams['phong_id'] = $hsbaParams['phong_id'];
                $phieuYLenhParams['auth_users_id'] = $hsbaParams['auth_users_id'];
                $phieuYLenhParams['loai_phieu_y_lenh'] = 2;
                $phieuYLenhParams['trang_thai'] = 0;
                $idPhieuYLenh = $this->phieuYLenhRepository->createDataPhieuYLenh($phieuYLenhParams);
                //set params y_lenh
                $yLenhParams['vien_phi_id'] = $idVienPhi;
                $yLenhParams['phieu_y_lenh_id'] = $idPhieuYLenh;
                $yLenhParams['doi_tuong_benh_nhan'] = $hsbaKpParams['doi_tuong_benh_nhan'];
                $yLenhParams['khoa_id'] = $hsbaParams['khoa_id'];
                $yLenhParams['phong_id'] = $hsbaParams['phong_id'];
                $yeuCauKham = $this->danhMucDichVuRepository->getDataDanhMucDichVuById($hsbaKpParams['yeu_cau_kham_id']);
                $yLenhParams['ma'] = $yeuCauKham['ma'];
                $yLenhParams['ten'] = $yeuCauKham['ten'];
                $yLenhParams['ten_nhan_dan'] = $yeuCauKham['ten_nhan_dan'];
                $yLenhParams['ten_bhyt'] = $yeuCauKham['ten_bhyt'];
                $yLenhParams['ten_nuoc_ngoai'] = $yeuCauKham['ten_nuoc_ngoai'];
                $yLenhParams['trang_thai'] = 0;
                $yLenhParams['gia'] = (double)$yeuCauKham['gia'];
                $yLenhParams['gia_nhan_dan'] = (double)$yeuCauKham['gia_nhan_dan'];
                $yLenhParams['gia_bhyt'] = (double)$yeuCauKham['gia_bhyt'];
                $yLenhParams['gia_nuoc_ngoai'] = (double)$yeuCauKham['gia_nuoc_ngoai'];
                $idYLenh = $this->yLenhRepository->createDataYLenh($yLenhParams);
                //sothutuphongkham
                return $idYLenh;
                
                
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
    
    
    public function createChuyenPhong(Request $request)
    {
        //1. tìm khoa hiện tại của bệnh nhân
        //2. có phòng chuyển tới tìm khoa của phòng nay
        //3. so sánh khoa hiện tại và khoa chuyển tới
        //4. nếu khoa bằng nhau chính là chuyển phòng
        //5. nếu khoa khác nhau chính là chuyển khoa
        //6. TH chuyển phòng tạo thêm dữ liệu ở số thứ tự phòng khám
        //7. TH chuyển khoa tạo dữ liệu mới ở hsba khoa phòng
        $benhNhanParams = $request->only('benh_nhan_id');
        $result = DB::transaction(function () use ( $benhNhanParams) {
            try {
                
            }
            catch (\Exception $ex) {
                 throw $ex;
            }
        });
        
        return $result;
    }
}
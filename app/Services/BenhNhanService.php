<?php

namespace App\Services;

use Illuminate\Http\Request;
use DB;
use App\Repositories\BenhNhan\BenhNhanRepository;
use App\Repositories\Hsba\HsbaRepository;
use App\Repositories\Hsba\HsbaKhoaPhongRepository; 
use App\Repositories\Bhyt\BhytRepository; 
use App\Repositories\VienPhi\VienPhiRepository; 
use App\Repositories\DanhMuc\DanhMucTongHopRepository;
use App\Repositories\DieuTri\DieuTriRepository;
use App\Repositories\PhieuYLenh\PhieuYLenhRepository;
use App\Repositories\DanhMuc\DanhMucDichVuRepository;
use App\Repositories\YLenh\YLenhRepository;
use App\Repositories\PhongRepository;
use App\Repositories\HanhChinhRepository;
use App\Services\SttPhongKhamService;
use App\Helper\Util;

use Validator;

class BenhNhanService{
   
    public function __construct(BenhNhanRepository $benhNhanRepository, HsbaRepository $hsbaRepository, HsbaKhoaPhongRepository $hsbaKhoaPhongRepository, DanhMucTongHopRepository $danhMucTongHopRepository, BhytRepository $bhytRepository, VienPhiRepository $vienPhiRepository, DieuTriRepository $dieuTriRepository, PhieuYLenhRepository $phieuYLenhRepository, DanhMucDichVuRepository $danhMucDichVuRepository, YLenhRepository $yLenhRepository, PhongRepository $phongRepository, SttPhongKhamService $sttPhongKhamService,HanhChinhRepository $hanhChinhRepository)
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
        $this->phongRepository = $phongRepository;
        $this->sttPhongKhamService = $sttPhongKhamService;
        $this->hanhChinhRepository = $hanhChinhRepository;
    }
    
    public function registerBenhNhan(Request $request)
    {
        //1. Kiểm tra thông tin bảo hiểm
        //2. Nếu có bảo hiểm thì bệnh nhân này đã tồn tại không tạo mới thông tin bệnh nhân
        //3. Nếu ko tìm thấy bảo hiểm tạo mới thông tin bệnh nhân
        //4. Tạo Hsba, hsba_khoa_phong, vien_phi, dieu_tri, phieu_y_lenh, y_lenh
        //kiểm tra thông tin scan
        $scan = $request->only('scan');
        //return $idBenhNhan;
        $arrayRequest = $request->all();
        $dataNgheNghiep = $this -> danhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('nghe_nghiep', $request['nghe_nghiep_id']);
        $dataDanToc =  $this -> danhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('dan_toc', $request['dan_toc_id']);
        $dataQuocTich =  $this -> danhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('quoc_tich', $request['quoc_tich_id']);
        $thxData = isset($request['thx_gplace_json']) ? $request['thx_gplace_json'] : null;
        $dataTinh = null;
        $dataHuyen = null;
        $dataXa = null;
        if($thxData)
        {
            $dataTenTHX = Util::getDataFromGooglePlace($request['thx_gplace_json']);
            $dataTinh = $this->hanhChinhRepository->getDataTinh(mb_convert_case($dataTenTHX['ten_tinh_thanh_pho'], MB_CASE_UPPER, "UTF-8"));
            $dataHuyen = $this->hanhChinhRepository->getDataHuyen($dataTinh['ma_tinh'], mb_convert_case($dataTenTHX['ten_quan_huyen'], MB_CASE_UPPER, "UTF-8"));
            $dataXa = $this->hanhChinhRepository->getDataXa($request['tinh_thanh_pho_id'], $request['quan_huyen_id'], $request['phuong_xa_id']);
        }
        
        $array = ['loai_nguoi_than', 'ten_nguoi_than', 'dien_thoai_nguoi_than'];
        $arrayLoaiNguoiThan =  $arrayRequest['loai_nguoi_than'];//[ 'Cha', 'Me','EM', 'Vo' ];
        $arrayTenNguoiThan = $arrayRequest['ten_nguoi_than'];
        $arrayDTNguoiThan = $arrayRequest['dien_thoai_nguoi_than'];
        $dataNguoiThan = [];
        $count = 1;

        foreach($array as $key=>$item) {
            if($count == 1) {
                foreach($arrayLoaiNguoiThan as $key1=>$item1) {
                    $dataNguoiThan[$key1][$item] =  $item1;
                }
            }
            if($count == 2) {
                foreach($arrayTenNguoiThan as $key2=>$item2) {
                    $dataNguoiThan[$key2][$item] =  $item2;
                }
            }
            if($count == 3) {
                foreach($arrayDTNguoiThan as $key3=>$item3) {
                    $dataNguoiThan[$key3][$item] =  $item3;
                }
            }
            $count++;
        }
        //set params benh_nhan 
        $benhNhanParams = $request->only('benh_nhan_id' , 'ho_va_ten', 'ngay_sinh', 'gioi_tinh_id'
                                        , 'so_nha', 'duong_thon', 'noi_lam_viec'
                                        , 'url_hinh_anh', 'dien_thoai_benh_nhan', 'email_benh_nhan', 'dia_chi_lien_he'
                                        );
        $hsbaParams = $request->only( 'auth_users_id', 'khoa_id'
                                                , 'ngay_sinh', 'gioi_tinh_id'
                                                , 'so_nha', 'duong_thon', 'noi_lam_viec'
                                                , 'url_hinh_anh', 'dien_thoai_benh_nhan', 'email_benh_nhan', 'dia_chi_lien_he'
                                                , 'ms_bhyt', 'benh_vien_id'
                                        );
        $hsbaKpParams = $request -> only ('auth_users_id', 'doi_tuong_benh_nhan', 'yeu_cau_kham_id', 'cdtd_icd10_text', 'cdtd_icd10_code'
                                            ,'benh_vien_id'
                                        );
        $bhytParams = $request -> only ('ma_cskcbbd', 'tu_ngay', 'den_ngay', 'ma_noi_song', 'du5nam6thangluongcoban', 'dtcbh_luyke6thang', 'tuyen_bhyt'
                                        );
        $bhytParams['image_url'] = $request->only('image_url_bhyt')['image_url_bhyt'];     
        $dieuTriParams = $request ->only ('cd_icd10_code', 'cd_icd10_text');        
        $sttPhongKhamParams =  $request ->only ('loai_stt', 'ma_nhom', 'stt_don_tiep_id');
        $result = DB::transaction(function () use ($thxData, $scan, $benhNhanParams, $hsbaParams,$hsbaKpParams, $dataNgheNghiep, $dataDanToc, $dataQuocTich, $dataTinh, $dataHuyen, $dataXa, $bhytParams, $sttPhongKhamParams, $dataNguoiThan ) {
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
                $tenBenhNhanInHoa = mb_convert_case($benhNhanParams['ho_va_ten'], MB_CASE_UPPER, "UTF-8");
                $benhNhanParams['ho_va_ten'] = $tenBenhNhanInHoa;
                $benhNhanParams['nghe_nghiep_id'] = $dataNgheNghiep['gia_tri'];
                $benhNhanParams['dan_toc_id'] = $dataDanToc['gia_tri'];
                $benhNhanParams['quoc_tich_id'] = $dataQuocTich['gia_tri'];
                $benhNhanParams['tinh_thanh_pho_id'] = $dataTinh['ma_tinh'];
                $benhNhanParams['quan_huyen_id'] = $dataHuyen['ma_huyen'];
                $benhNhanParams['phuong_xa_id'] = $dataXa['ma_xa'];
                $benhNhanParams['nam_sinh'] =  str_limit($benhNhanParams['ngay_sinh'], 4,'');
                $benhNhanParams['nguoi_than'] = json_encode($dataNguoiThan);
                if($idBenhNhan == null)//insert tbl benh_nhan
                     $idBenhNhan = $this->benhNhanRepository->createDataBenhNhan($benhNhanParams);
                else 
                    $idBenhNhan = $idBenhNhan['benh_nhan_id'];
                //set params hsba 
                $hsbaParams['loai_benh_an'] = 24;
                $hsbaParams['hinh_thuc_vao_vien'] = 2;
                $hsbaParams['trang_thai_hsba'] = 0;
                $hsbaParams['benh_nhan_id'] = $idBenhNhan;
                $hsbaParams['ten_benh_nhan'] = $tenBenhNhanInHoa;
                $hsbaParams['ten_benh_nhan_khong_dau'] = Util::convertViToEn($tenBenhNhanInHoa);
                $hsbaParams['is_dang_ky_truoc'] = 0;
                $hsbaParams['ten_nghe_nghiep'] = $dataNgheNghiep['dien_giai'];
                $hsbaParams['ten_dan_toc'] = $dataDanToc['dien_giai'];
                $hsbaParams['ten_quoc_tich'] = $dataQuocTich['dien_giai'];
                $hsbaParams['nghe_nghiep_id'] = $dataNgheNghiep['gia_tri'];
                $hsbaParams['dan_toc_id'] = $dataDanToc['gia_tri'];
                $hsbaParams['quoc_tich_id'] = $dataQuocTich['gia_tri'];
                //chưa xử id
                $hsbaParams['tinh_thanh_pho_id'] = $dataTinh['ma_tinh'];
                $hsbaParams['quan_huyen_id'] = $dataHuyen['ma_huyen'];
                $hsbaParams['phuong_xa_id'] = $dataXa['ma_xa'];
                $hsbaParams['nam_sinh'] =  str_limit($benhNhanParams['ngay_sinh'], 4,'');
                $hsbaParams['nguoi_than'] = json_encode($dataNguoiThan);
                 //insert hsba
                $idHsba = $this->hsbaRepository->createDataHsba($hsbaParams);
                //set params hsba_khoa_phong
                $hsbaKpParams['khoa_hien_tai'] = $hsbaParams['khoa_id'];
                $hsbaKpParams['hsba_id'] = $idHsba;
                $hsbaKpParams['trang_thai'] = 0;
                $hsbaKpParams['loai_benh_an'] = 24;
                $hsbaKpParams['benh_nhan_id'] = $idBenhNhan;
                $hsbaKpParams['hinh_thuc_vao_vien_id'] = 2;
                $hsbaKpParams['bhyt_id'] = $idBhyt;
                //insert hsba_khoa_phong
                $idHsbaKp = $this->hsbaKhoaPhongRepository->createData($hsbaKpParams);
                //sothutuphongkham
                $yeuCauKham = $this->danhMucDichVuRepository->getDataDanhMucDichVuById($hsbaKpParams['yeu_cau_kham_id']);
                $sttPhongKhamParams['benh_nhan_id'] = $idBenhNhan;
                $sttPhongKhamParams['ten_benh_nhan'] = $tenBenhNhanInHoa;
                $sttPhongKhamParams['gioi_tinh_id'] = $benhNhanParams['gioi_tinh_id'];
                $sttPhongKhamParams['ms_bhyt'] = $hsbaParams['ms_bhyt'];
                $sttPhongKhamParams['yeu_cau_kham'] = $yeuCauKham['ten'];
                $sttPhongKhamParams['khoa_id'] = $hsbaParams['khoa_id'];
                $sttPhongKhamParams['benh_vien_id'] = $hsbaParams['benh_vien_id'];
                $sttPhongKhamParams['hsba_id'] = $idHsba;
                $sttPhongKhamParams['hsba_khoa_phong_id'] = $idHsbaKp;
                $data = $this->sttPhongKhamService->getSttPhongKham($sttPhongKhamParams);
                //set params vien_phi
                $vienPhiParams['loai_vien_phi'] = $hsbaKpParams['doi_tuong_benh_nhan'] == 1 ? 4 : 1;
                $vienPhiParams['trang_thai'] = 0;
                $vienPhiParams['khoa_id'] = $hsbaParams['khoa_id'];
                $vienPhiParams['doi_tuong_benh_nhan'] = $hsbaKpParams['doi_tuong_benh_nhan'];
                $vienPhiParams['bhyt_id'] = $idBhyt;
                $vienPhiParams['benh_nhan_id'] = $idBenhNhan;
                $vienPhiParams['hsba_id'] = $idHsba;
                $vienPhiParams['trang_thai_thanh_toan_bh'] = 0;
                //insert vien_phi
                $idVienPhi = $this->vienPhiRepository->createDataVienPhi($vienPhiParams);
                $phongId = $data['phong_id'];
                //update phong_id từ stt_phong_kham
                //$this->hsbaRepository->updateHsba($idHsba, $thxData);
                $this->hsbaRepository->updateHsba($idHsba, ['phong_id' => $phongId, 'thx_gplace_json' => $thxData]);
                $this->hsbaKhoaPhongRepository->update($idHsbaKp, ['phong_hien_tai' => $phongId, 'vien_phi_id' => $idVienPhi]);
                $this->vienPhiRepository->updateVienPhi($idVienPhi, ['phong_id' => $phongId]);
                //set params dieu_tri
                $dieuTriParams['hsba_khoa_phong_id'] = $idHsbaKp;
                $dieuTriParams['hsba_id'] = $idHsba;
                $dieuTriParams['khoa_id'] = $hsbaParams['khoa_id'];
                $dieuTriParams['phong_id'] = $phongId;
                $dieuTriParams['auth_users_id'] = $hsbaParams['auth_users_id'];
                $dieuTriParams['benh_nhan_id'] = $idBenhNhan;
                $dieuTriParams['ten_benh_nhan'] = $tenBenhNhanInHoa;
                $dieuTriParams['nam_sinh'] = str_limit($benhNhanParams['ngay_sinh'], 4,'');
                $dieuTriParams['gioi_tinh_id'] = $benhNhanParams['gioi_tinh_id'];
                //insert dieu_tri
                $idDieuTri = $this->dieuTriRepository->createDataDieuTri($dieuTriParams);
                //set params phieu_y_lenh
                $phieuYLenhParams['benh_nhan_id'] = $idBenhNhan;
                $phieuYLenhParams['vien_phi_id'] = $idVienPhi;
                $phieuYLenhParams['hsba_id'] = $idHsba;
                $phieuYLenhParams['dieu_tri_id'] = $idDieuTri;
                $phieuYLenhParams['khoa_id'] = $hsbaParams['khoa_id'];
                $phieuYLenhParams['phong_id'] = $phongId;
                $phieuYLenhParams['auth_users_id'] = $hsbaParams['auth_users_id'];
                $phieuYLenhParams['loai_phieu_y_lenh'] = 2;
                $phieuYLenhParams['trang_thai'] = 0;
                $idPhieuYLenh = $this->phieuYLenhRepository->createDataPhieuYLenh($phieuYLenhParams);
                //set params y_lenh
                $yLenhParams['vien_phi_id'] = $idVienPhi;
                $yLenhParams['phieu_y_lenh_id'] = $idPhieuYLenh;
                $yLenhParams['doi_tuong_benh_nhan'] = $hsbaKpParams['doi_tuong_benh_nhan'];
                $yLenhParams['khoa_id'] = $hsbaParams['khoa_id'];
                $yLenhParams['phong_id'] = $phongId;
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
                return $data;
                
            } catch (\Exception $ex) {
                 throw $ex;
            }
        });
        
        return $result;
    }
}
<?php
namespace App\Services;
use DB;
use App\Repositories\DieuTri\DieuTriRepository;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use App\Repositories\Hsba\HsbaKhoaPhongRepository;
use App\Repositories\Hsba\HsbaPhongKhamRepository;
use App\Repositories\Hsba\HsbaRepository;
use App\Repositories\VienPhi\VienPhiRepository;
use App\Repositories\PhongRepository;
use App\Repositories\RaVienRepository;
use App\Repositories\ChuyenVienRepository;
use App\Services\SttPhongKhamService;
use App\Helper\AwsS3;

class DieuTriService
{
    //xử trí
    const XT_CHUYEN_PHONG_KHAM = 1;
    const XT_KET_THUC_KHAM = 3;
    const XT_TRA_BN_KHONG_KHAM = 6;
    const XT_BO_VE = 7;
    const XT_CHUYEN_KHOA = 10;
    const XT_RA_VIEN = 11;
    const XT_HEN = 16;
    const XT_KHAC = 17;
    const XT_CHUYEN_VIEN = 13;
    const XT_CAP_TOA_CHO_VE = 4;
    
    //trạng thái hsba khoa phòng
    const TT_KET_THUC_DIEU_TRI = 99;
    const TT_CHO_DIEU_TRI = 0;
    const TT_DANG_DIEU_TRI = 2;
    
    //trang thai hsba
    const DONG_HSBA = 1;
    
    //hình thức ra viện
    const RA_VIEN = 1;
    const BO_VE = 3;
    const CHUYEN_KHOA = 8;
    
    //hình thức vào viện
    const NHAN_TU_KKB = 2;
    
    //loại phòng
    const PHONG_DIEU_TRI_NOI_TRU = 3;
    const PHONG_DIEU_TRI_NGOAI_TRU = 9;
    const PHONG_HANH_CHINH = 1;
    
    //loại bệnh án
    const BENH_AN_KHAM_BENH = 24;
    
    //viện phí
    const VP_TRANG_THAI = 1;
    
    // Chuyển viện
    const LOAI_CHUYEN_VIEN = 1;
    
    public function __construct
    (
        DieuTriRepository $dieuTriRepository, 
        HsbaKhoaPhongRepository $hsbaKhoaPhongRepository, 
        HsbaRepository $hsbaRepository, 
        VienPhiRepository $vienPhiRepository, 
        PhongRepository $phongRepository,
        SttPhongKhamService $sttPhongKhamService,
        RaVienRepository $raVienRepository,
        HsbaPhongKhamRepository $hsbaPhongKhamRepository,
        ChuyenVienRepository $chuyenVienRepository
    )
    {
        $this->dieuTriRepository = $dieuTriRepository;
        $this->hsbaKhoaPhongRepository = $hsbaKhoaPhongRepository;
        $this->hsbaPhongKhamRepository = $hsbaPhongKhamRepository;
        $this->hsbaRepository = $hsbaRepository;
        $this->vienPhiRepository = $vienPhiRepository;
        $this->phongRepository = $phongRepository;
        $this->sttPhongKhamService = $sttPhongKhamService;
        $this->raVienRepository = $raVienRepository;
        $this->chuyenVienRepository = $chuyenVienRepository;
    }
    
    public function updateInfoDieuTri(array $dieuTriParams)
    {
        //Phiều điều trị của khoa khám bệnh đc tạo thời điểm đăng ký khám bệnh
        //1 lấy phiếu điều trị dựa vào hsba_khoa_phong_id
        //2. update thông tin phiếu điều trị
        $result = DB::transaction(function () use ($dieuTriParams) {
            try {
                $dataDieuTri = $this->dieuTriRepository->getDieuTriByHsba_Kp($dieuTriParams['hsba_khoa_phong_id'], $dieuTriParams['khoa_id'], $dieuTriParams['phong_id']); 
                $dieuTriParams['thoi_gian_chi_dinh'] = Carbon::now()->toDateTimeString();
                //$this->dieuTriRepository->updateDieuTri($dataDieuTri['id'], $dieuTriParams);
                //cập nhật hsba_khoa_phong
                $hsbaKpParams = null;
                $input = array_where($dieuTriParams, function ($value, $key) {
                        return $value != '';
                });
                $input = array_except($input, ['hsba_khoa_phong_id', 'thoi_gian_chi_dinh', 'khoa_id']);
                
                $fileUpload = [];
                // Config S3
                $s3 = new AwsS3();
                
                // GET OLD FILE
                $item = $this->hsbaPhongKhamRepository->getByHsbaKpId($dieuTriParams['hsba_khoa_phong_id']);
                $fileItem =  isset($item->upload_file_kham_benh) ? json_decode($item->upload_file_kham_benh, true) : [];
                
                // Remove File old
                if(!empty($input['oldFiles'])) {
                    foreach($fileItem as $file) {
                        if(!in_array($file, $input['oldFiles'])) {
                            $s3->deleteObject($file);
                        }
                        else {
                            $fileUpload[] = $file;
                        }
                    }
                    unset($input['oldFiles']);
                }
                else {
                    if(!empty($fileItem)) {
                        foreach($fileItem as $file) {
                            $s3->deleteObject($file);
                        }
                    }
                }
                
                if(!empty($input['files'])) {
                    foreach ($input['files'] as $file) {
                        $imageFileName = time() . '_' . rand(0, 999999) . '.' . $file->getClientOriginalExtension();
                        $fileUpload[] = $imageFileName;
                        
                        $pathName = $file->getPathName();
                        $mimeType = $file->getMimeType();
                        $result = $s3->putObject($imageFileName, $pathName, $mimeType);
                    }
                    unset($input['files']);
                }
                
                if(!empty($fileUpload)) {
                    $input['upload_file_kham_benh'] = json_encode($fileUpload);
                }
                else {
                    $input['upload_file_kham_benh'] = NULL;
                }
                $this->hsbaPhongKhamRepository->update($dieuTriParams['hsba_khoa_phong_id'], $input);
            } catch (\Exception $ex) {
                 throw $ex;
            }
        });
        return $result;
    }
    
    public function xuTriBenhNhan(array $request)
    {
        switch ($request['xu_tri']) {
            case self::XT_CHUYEN_PHONG_KHAM:     
            case self::XT_CHUYEN_KHOA:   
                $data = $this->createChuyenPhong($request);
                return $data;
                break;
            case self::XT_KET_THUC_KHAM: 
            case self::XT_TRA_BN_KHONG_KHAM:
            case self::XT_BO_VE:     
                $this->createKetThucKham($request);
                return [];
                break;
            case self::XT_RA_VIEN:
            case self::XT_CAP_TOA_CHO_VE:
            case self::XT_HEN:
            case self::XT_KHAC:
                $this->createRaVien($request);
                return [];
                break;
            case self::XT_CHUYEN_VIEN:   
                $this->createChuyenVien($request);
                return [];
                break;
        }
    }
    
    private function createChuyenPhong(array $request)
    {
        //1. tìm khoa, phòng hiện tại của bệnh nhân
        //1.1 viện phí ?
        //2. so sánh khoa hiện tại và khoa chuyển tới
        //2.1 so sánh khoa hiện tại và khoa chuyển tới -> khoa = nhau chính là chuyển phòng -> chuyển phòng khám -> tạo stt_pk -> update khoa, phòng hiện tại
        //2.2 so sánh khoa hiện tại và khoa chuyển tới -> khác khoa chính là chuyển khoa -> chuyển khoa (chuyển bn này vào hành chính khoa) (chưa tính tới TH trả về phòng khám)
        $result = DB::transaction(function () use ($request) {
            $khoaChuyenDen = $request['khoa_chuyen_den'] ? $request['khoa_chuyen_den'] : null;
            try {
                //1
                $hsbaKp = $this->hsbaKhoaPhongRepository->getById($request['hsba_khoa_phong_id']);
                if($hsbaKp == null) 
                    return 'không tìm thấy hsba_khoa_phong';
                if($hsbaKp['trang_thai'] == self::TT_KET_THUC_DIEU_TRI)
                    return 'hsba_khoa_phong này đã đóng';
                if($khoaChuyenDen == null)
                    $khoaChuyenDen = $hsbaKp['khoa_hien_tai'];
                    
                //1.1
                //viện phí ?
                $khoaHienTai = $hsbaKp['khoa_hien_tai'];
                $phongHienTai = $hsbaKp['phong_hien_tai'];
                
                switch ($khoaHienTai) {
                    case $khoaChuyenDen ://2.1 chuyển phòng
                        //thêm stt_phong_kham
                        $sttPhongKhamParams['loai_stt'] = $request['loai_stt'];
                        $sttPhongKhamParams['ma_nhom'] = $request['ma_nhom'];
                        $sttPhongKhamParams['stt_don_tiep_id'] = $request['stt_don_tiep_id'];
                        $sttPhongKhamParams['benh_nhan_id'] = $hsbaKp['benh_nhan_id'];
                        $sttPhongKhamParams['ten_benh_nhan'] = $request['ten_benh_nhan'];
                        $sttPhongKhamParams['gioi_tinh_id'] = $request['gioi_tinh_id'];
                        $sttPhongKhamParams['ms_bhyt'] = $request['ms_bhyt'];
                        $sttPhongKhamParams['yeu_cau_kham'] = $hsbaKp['yeu_cau_kham'];
                        $sttPhongKhamParams['khoa_id'] = $khoaChuyenDen;
                        $sttPhongKhamParams['benh_vien_id'] = $hsbaKp['benh_vien_id'];
                        $sttPhongKhamParams['hsba_id'] = $hsbaKp['hsba_id'];
                        $sttPhongKhamParams['hsba_khoa_phong_id'] = $hsbaKp['id'];
                        $data = $this->sttPhongKhamService->getSttPhongKham($sttPhongKhamParams);
                        
                        //tạo phiếu điều trị đối vs phòng chuyển đến
                        $dieuTriParams['hsba_khoa_phong_id'] = $hsbaKp['id'];
                        $dieuTriParams['hsba_id'] = $hsbaKp['hsba_id'];
                        $dieuTriParams['khoa_id'] = $khoaHienTai;
                        $dieuTriParams['phong_id'] = $data['phong_id'];
                        $dieuTriParams['benh_nhan_id'] = $hsbaKp['benh_nhan_id'];;
                        $dieuTriParams['ten_benh_nhan'] = $request['ten_benh_nhan'];
                        $dieuTriParams['nam_sinh'] = $request['nam_sinh'];
                        $dieuTriParams['gioi_tinh_id'] = $request['gioi_tinh_id'];
                        $idDieuTri = $this->dieuTriRepository->createDataDieuTri($dieuTriParams);
                        
                        //update phong_hien_tai chuyển tới của hsba_khoa_phong hiện tại 
                        $hsbaKpParams['phong_hien_tai'] = $data['phong_id'];
                        $this->hsbaKhoaPhongRepository->update($hsbaKp['id'], $hsbaKpParams);
                        return $data;
                    break;    
                    
                    default://2.2 chuyển khoa
                        //update hsba_khoa_phong hiện tại
                        $this->updateHsbaKhoaPhongByXuTri($hsbaKp['id'], $request, self::TT_KET_THUC_DIEU_TRI, self::CHUYEN_KHOA); //99: kết thúc điều trị; 8: chuyển khoa
                        
                        //tạo hsba_khoa_phong 
                        $hsbaKpParams = null;
                        $hsbaKpParams['doi_tuong_benh_nhan'] = $hsbaKp['doi_tuong_benh_nhan'];
                        $hsbaKpParams['yeu_cau_kham_id'] = $hsbaKp['yeu_cau_kham_id'];
                        $hsbaKpParams['cdtd_icd10_text'] = $request['cdtd_icd10_text'] == $hsbaKp['cdtd_icd10_text'] ? $hsbaKp['cdtd_icd10_text'] : $request['cdtd_icd10_text'];
                        $hsbaKpParams['cdtd_icd10_code'] = $request['cdtd_icd10_code'] == $hsbaKp['cdtd_icd10_code'] ? $hsbaKp['cdtd_icd10_code'] : $request['cdtd_icd10_code'];
                        $hsbaKpParams['benh_vien_id'] = $hsbaKp['benh_vien_id'];
                        $hsbaKpParams['khoa_hien_tai'] = $khoaChuyenDen;
                        
                        //phòng hành chính của khoa chuyển đến
                        $phong = $this->phongRepository->getPhongHanhChinhByKhoaID($khoaChuyenDen);
                        $hsbaKpParams['phong_hien_tai'] = $phong->id;
                        $hsbaKpParams['loai_benh_an'] = $phong->loai_benh_an;
                        $hsbaKpParams['trang_thai'] = self::TT_CHO_DIEU_TRI; //0: chờ điều trị
                        $hsbaKpParams['hsba_id'] = $hsbaKp['hsba_id'];
                        $hsbaKpParams['benh_nhan_id'] = $hsbaKp['benh_nhan_id'];
                        $hsbaKpParams['hinh_thuc_vao_vien_id'] = self::NHAN_TU_KKB; //2: nhận từ khoa khám bệnh
                        $hsbaKpParams['vien_phi_id'] = $hsbaKp['vien_phi_id'];
                        $hsbaKpParams['bhyt_id'] = $hsbaKp['bhyt_id'];
                        
                        //kiểm tra phòng chuyển đến có phải là phòng điều trị -> nếu đúng -> lấy trạng thái = 2: đang điều trị ngược lại 0: đang chờ điều trị
                        $hsbaKpParams['trang_thai'] = $phong->loai_phong == self::PHONG_DIEU_TRI_NOI_TRU || $phong->loai_phong == self::PHONG_DIEU_TRI_NGOAI_TRU ? self::TT_DANG_DIEU_TRI : self::TT_CHO_DIEU_TRI; 
                        $idHsbaKp = $this->hsbaKhoaPhongRepository->createData($hsbaKpParams);
                        
                        //update hsba.loai_benh_an
                        $hsbaParams = null;
                        $hsbaParams['loai_benh_an'] = $phong->loai_benh_an;
                        $this->updateHsba($hsbaKp['hsba_id'], $hsbaParams);
                        
                        // tao dieu tri
                        $dataDieuTri['hsba_khoa_phong_id'] = $idHsbaKp;
                        $dataDieuTri['hsba_id'] = $hsbaKp['hsba_id'];
                        $dataDieuTri['khoa_id'] = $hsbaKpParams['khoa_hien_tai'];
                        $dataDieuTri['phong_id'] = $hsbaKpParams['phong_hien_tai'];
                        $dataDieuTri['benh_nhan_id'] =  $hsbaKp['benh_nhan_id'];
                        $dataDieuTri['id'] = $this->dieuTriRepository->createDataDieuTri($dataDieuTri);
                        return [];
                    break;
                }
            }
            catch (\Exception $ex) {
                 throw $ex;
            }
        });
        return $result;
    }
    
    private function updateHsbaKhoaPhongByXuTri($hsbaKp, array $request, $trang_thai, $hinh_thuc_ra_vien)
    {
        $input = array_where($request, function ($value, $key) {
                        return $value != '';
                });
        $input['trang_thai'] = $trang_thai; 
        $input['hinh_thuc_ra_vien'] = $hinh_thuc_ra_vien;
        $input['thoi_gian_ra_vien'] = Carbon::now()->toDateTimeString();
        $input = array_except($input, ['hsba_khoa_phong_id', 'khoa_chuyen_den', 'phong_chuyen_den', 'ten_benh_nhan', 'nam_sinh',
                                        'xu_tri', 'giai_phau_benh', 'loai_stt', 'gioi_tinh_id', 'stt_don_tiep_id', 'ms_bhyt', 'khoa_id']);
        $this->hsbaKhoaPhongRepository->update($hsbaKp, $input);
    }
    
    private function updateHsba($hsbaId, $hsba)
    {
        $this->hsbaRepository->updateHsba($hsbaId, $hsba);
    }
    
    private function createKetThucKham(array $request)
    {
        
        $result = DB::transaction(function () use ($request) {
            try {
                $hsbaKp = $this->hsbaKhoaPhongRepository->getById($request['hsba_khoa_phong_id']);
                if($hsbaKp == null) 
                    return 'không tìm thấy hsba_khoa_phong';
                if($hsbaKp['trang_thai'] == self::TT_KET_THUC_DIEU_TRI)
                    return 'hsba_khoa_phong này đã đóng';
                
                //cập nhật hsba_khoa_phong
                switch ($request['xu_tri']) {
                    case self::XT_BO_VE: 
                        $hinh_thuc_ra_vien = self::BO_VE;
                        break;
                    case self::XT_KET_THUC_KHAM: 
                    case self::XT_TRA_BN_KHONG_KHAM:   
                    case self::XT_RA_VIEN:
                    case self::XT_CHUYEN_VIEN:
                    case self::XT_CAP_TOA_CHO_VE:
                    case self::XT_HEN:
                    case self::XT_KHAC:
                        $hinh_thuc_ra_vien = self::RA_VIEN;
                        break;
                }
                $request['thoi_gian_ra_vien'] = Carbon::now()->toDateTimeString();
                $this->updateHsbaKhoaPhongByXuTri($hsbaKp['id'], $request, self::TT_KET_THUC_DIEU_TRI, $hinh_thuc_ra_vien);
                
                //cập nhật hsba
                $hsbaParams = null;
                $hsbaParams['cdrv_icd10_code'] = $request['cdrv_icd10_code'] ? $request['cdrv_icd10_code'] : $hsbaKp['cdrv_icd10_code'];
                $hsbaParams['cdrv_icd10_text'] = $request['cdrv_icd10_text'] ? $request['cdrv_icd10_text'] : $hsbaKp['cdrv_icd10_text'];
                $hsbaParams['cdrv_kem_theo_icd10_code'] = $request['cdrv_kt_icd10_code'] ? $request['cdrv_kt_icd10_code'] : $hsbaKp['cdrv_kt_icd10_code'];
                $hsbaParams['cdrv_kem_theo_icd10_text'] = $request['cdrv_kt_icd10_text'] ? $request['cdrv_kt_icd10_text'] : $hsbaKp['cdrv_kt_icd10_text'];
                $hsbaParams['trang_thai_hsba'] = self::DONG_HSBA;
                $hsbaParams['hinh_thuc_ra_vien'] = $hinh_thuc_ra_vien;
                $hsbaParams['ket_qua_dieu_tri'] = $request['ket_qua_dieu_tri'];
                $hsbaParams['ngay_ra_vien'] = Carbon::now()->toDateTimeString();
                $this->hsbaRepository->updateHsba($hsbaKp['hsba_id'], $hsbaParams);
                
                //cập nhật viện phí
                $vienPhiParams = null;
                $vienPhiParams['trang_thai'] = self::VP_TRANG_THAI;
                $this->vienPhiRepository->updateVienPhi($hsbaKp['vien_phi_id'], $vienPhiParams);
            }
            catch (\Exception $ex) {
                 throw $ex;
            }
        });
        return $result;    
    }
    
    private function createRaVien(array $request)
    {
        $result = DB::transaction(function () use ($request) {
            try {
                $raVienParams = null;
                $raVienParams['hsba_khoa_phong_id']=$request['hsba_khoa_phong_id']?$request['hsba_khoa_phong_id']:'';
                $raVienParams['benh_nhan_id']=$request['benh_nhan_id']?$request['benh_nhan_id']:'';
                $raVienParams['thoi_gian_ra_vien']=$request['thoi_gian_ra_vien']?$request['thoi_gian_ra_vien']:'';
                $raVienParams['tinh_trang']=$request['tinh_trang']?$request['tinh_trang']:'';
                $raVienParams['phuong_phap_dieu_tri']=$request['phuong_phap_dieu_tri']?$request['phuong_phap_dieu_tri']:'';
                $raVienParams['huong_dieu_tri_tiep_theo']=$request['huong_dieu_tri_tiep_theo']?$request['huong_dieu_tri_tiep_theo']:'';
                $raVienParams['lich_hen']=$request['lich_hen']?$request['lich_hen']:'';
                $raVienParams['loi_dan_bac_si']=$request['loi_dan_bac_si']?$request['loi_dan_bac_si']:'';
                //1.Kiểm tra bệnh nhân có xử trí trước đó hay chưa
                
                $raVien = $this->raVienRepository->getById($request['hsba_khoa_phong_id'], $request['benh_nhan_id']);
                if(empty($raVien)){
                    $this->raVienRepository->createRaVien($raVienParams);
                }
                else{
                    $this->raVienRepository->updateRaVien($raVien->id,$raVienParams);
                }
                //2.Update hsba khoa phòng & hsba & viện phí
                $request = array_except($request,['thoi_gian_ra_vien','tinh_trang', 'phuong_phap_dieu_tri','huong_dieu_tri_tiep_theo','lich_hen','loi_dan_bac_si']);
                $this->createKetThucKham($request);
                
            }
            catch (\Exception $ex) {
                 throw $ex;
            }
        });
        return $result;
    }
    
    private function createChuyenVien(array $params) {
        $result = DB::transaction(function () use ($params) {
            try {
                $dataChuyenVien['khoa_id'] = isset($params['khoa_id'])?$params['khoa_id']:null;
                $dataChuyenVien['hsba_khoa_phong_id'] = isset($params['hsba_khoa_phong_id'])?$params['hsba_khoa_phong_id']:null;
                $dataChuyenVien['benh_nhan_id'] = isset($params['benh_nhan_id'])?$params['benh_nhan_id']:null;
                $dataChuyenVien['thoi_gian_chuyen_vien'] = isset($params['chuyen_tuyen_hoi'])?$params['chuyen_tuyen_hoi']:null;
                $dataChuyenVien['ma_benh_vien_chuyen_toi'] = isset($params['benh_vien_tuyen_duoi_code'])?$params['benh_vien_tuyen_duoi_code']:null;
                $dataChuyenVien['tinh_trang_nguoi_benh'] = isset($params['tinh_trang_nguoi_benh'])?$params['tinh_trang_nguoi_benh']:null;
                $dataChuyenVien['phuong_tien_van_chuyen'] = isset($params['phuong_tien_van_chuyen'])?$params['phuong_tien_van_chuyen']:null;
                $dataChuyenVien['nguoi_van_chuyen'] = isset($params['nguoi_van_chuyen'])?$params['nguoi_van_chuyen']:null;
                $dataChuyenVien['dau_hieu_lam_sang'] = isset($params['dau_hieu_lam_sang'])?$params['dau_hieu_lam_sang']:null;
                $dataChuyenVien['thuoc'] = isset($params['thuoc'])?$params['thuoc']:null;
                $dataChuyenVien['xet_nghiem'] = isset($params['xet_nghiem'])?$params['xet_nghiem']:null;
                $dataChuyenVien['huong_dieu_tri'] = isset($params['huong_dieu_tri'])?$params['huong_dieu_tri']:null;
                $dataChuyenVien['chan_doan'] = isset($params['chan_doan'])?$params['chan_doan']:null;
                $dataChuyenVien['ly_do_chuyen_vien_id'] = isset($params['ly_do_chuyen_vien_id'])?$params['ly_do_chuyen_vien_id']:null;
                $dataChuyenVien['hinh_thuc_chuyen_vien_id'] = isset($params['hinh_thuc_chuyen_vien_id'])?$params['hinh_thuc_chuyen_vien_id']:null;
                $dataChuyenVien['tuyen_id'] = isset($params['tuyen_id'])?$params['tuyen_id']:null;
                $dataChuyenVien['chan_doan_tuyen_duoi_code'] = isset($params['chan_doan_tuyen_duoi_code'])?$params['chan_doan_tuyen_duoi_code']:null;
                $dataChuyenVien['chan_doan_tuyen_duoi_text'] = isset($params['chan_doan_tuyen_duoi'])?$params['chan_doan_tuyen_duoi']:null;
                $dataChuyenVien['loai_chuyen_vien_id'] = self::LOAI_CHUYEN_VIEN;
                //insert chuyen_vien
                $this->chuyenVienRepository->createData($dataChuyenVien);
                //2.Update hsba khoa phòng & hsba & viện phí
                $params = array_except($params,['chuyen_tuyen_hoi',
                                        'benh_vien_tuyen_duoi_code', 'benh_vien_tuyen_duoi', 'dau_hieu_lam_sang', 'hinh_thuc_chuyen_vien_id',
                                        'tuyen_id', 'ly_do_chuyen_vien_id', 'xet_nghiem', 'huong_dieu_tri', 'chan_doan', 'phuong_tien_van_chuyen',
                                        'thuoc', 'nguoi_van_chuyen', 'tinh_trang_nguoi_benh', 'khoa_id']);
                $this->createKetThucKham($params);
                
            }
            catch (\Exception $ex) {
                 throw $ex;
            }
        });
        return $result;
    }    
    
    public function getPhieuDieuTri(array $input)
    {
        $result = $this->dieuTriRepository->getPhieuDieuTri($input);
        return $result;
    }
}
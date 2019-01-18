<?php

namespace App\Services;

use Illuminate\Http\Request;

use DB;

// Repositories
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
use App\Repositories\ChuyenVienRepository;

// Service
use App\Services\SttPhongKhamService;
use App\Services\HsbaKhoaPhongService;
use App\Services\VienPhiService;

// Others
use App\Helper\Util;
use Carbon\Carbon;

//Value objects
use App\Models\ValueObjects\NhomNguoiThan;

use Validator;
use App\Helper\AwsS3;

class BenhNhanServiceV2 {
    
    private $dataBenhNhan = [];
    private $dataHsba = [];
    private $dataHsbaKp = [];
    private $dataYeuCauKham = [];
    private $dataSttPk = [];
    private $dataVienPhi = [];
    private $dataDieuTri = [];
    private $dataPhieuYLenh = [];
    private $dataYLenh = [];
    private $dataNgheNghiep = [];
    private $dataDanToc = [];
    private $dataQuocTich = [];
    private $dataTinh = [];
    private $dataHuyen = [];
    private $dataXa = [];
    //private $dataTHX = null;
    private $dataTenTHX = [];
    private $dataNhomNguoiThan = null;
    private $dataChuyenVien = [];
    
    private $dataLog = [];
    
    private $benhNhanKeys = [
        'benh_nhan_id', 'ho_va_ten', 'ngay_sinh', 'gioi_tinh_id'
        , 'so_nha', 'duong_thon', 'noi_lam_viec'
        , 'url_hinh_anh', 'dien_thoai_benh_nhan', 'email_benh_nhan', 'dia_chi_lien_he'
        , 'tinh_thanh_pho_id' , 'quan_huyen_id' , 'phuong_xa_id'
    ];
    
    private $hsbaKeys = [
        'auth_users_id', 'khoa_id'
        , 'ngay_sinh', 'gioi_tinh_id'
        , 'so_nha', 'duong_thon', 'noi_lam_viec'
        , 'url_hinh_anh', 'dien_thoai_benh_nhan', 'email_benh_nhan', 'dia_chi_lien_he'
        , 'ms_bhyt', 'benh_vien_id'
        , 'tinh_thanh_pho_id' , 'quan_huyen_id' , 'phuong_xa_id'
    ];
    
    private $hsbaKpKeys = [
        'auth_users_id', 'doi_tuong_benh_nhan', 'yeu_cau_kham_id', 'cdtd_icd10_text', 'cdtd_icd10_code'
        ,'benh_vien_id'
    ];
    
    private $bhytKeys = ['ms_bhyt', 'ma_cskcbbd', 'tu_ngay', 'den_ngay', 'ma_noi_song', 'du5nam6thangluongcoban', 'dtcbh_luyke6thang', 'tuyen_bhyt'];
    
    private $sttPkKeys = [
        'loai_stt', 'ma_nhom', 'stt_don_tiep_id'
    ];
    
    private $dieuTriKeys = [
        'cd_icd10_code', 'cd_icd10_text'
    ];
    
    private $chuyenVienKeys = [
        'khoa_id', 'chuyen_tuyen_hoi','benh_vien_tuyen_duoi_code',
        'tinh_trang_chuyen_tuyen','ly_do_chuyen_tuyen','phuong_tien_van_chuyen',
        'ten_nguoi_ho_tong','dau_hieu_lam_sang','phuong_phap_thu_thuat','ket_qua_xet_nghiem',
        'huong_dieu_tri','chan_doan','chan_doan_tuyen_duoi_code','chan_doan_tuyen_duoi'
    ];    
    
    public function __construct
    (
        HsbaKhoaPhongService $hsbaKhoaPhongService,
        SttPhongKhamService $sttPhongKhamService,
        VienPhiService $vienPhiService,
        
        BenhNhanRepository $benhNhanRepository, 
        HsbaRepository $hsbaRepository, 
        HsbaKhoaPhongRepository $hsbaKhoaPhongRepository, 
        DanhMucTongHopRepository $danhMucTongHopRepository, 
        BhytRepository $bhytRepository, 
        VienPhiRepository $vienPhiRepository, 
        DieuTriRepository $dieuTriRepository, 
        PhieuYLenhRepository $phieuYLenhRepository, 
        DanhMucDichVuRepository $danhMucDichVuRepository, 
        YLenhRepository $yLenhRepository, 
        PhongRepository $phongRepository, 
        HanhChinhRepository $hanhChinhRepository,
        ChuyenVienRepository $chuyenVienRepository
    )
    {
        // Services
        $this->sttPhongKhamService = $sttPhongKhamService;
        $this->hsbaKhoaPhongService = $hsbaKhoaPhongService;
        $this->vienPhiService = $vienPhiService;
        
        // Repositories
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
        $this->hanhChinhRepository = $hanhChinhRepository;
        $this->chuyenVienRepository = $chuyenVienRepository;
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
        
        $this->dataNgheNghiep = $this->danhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('nghe_nghiep', $request['nghe_nghiep_id']);
        $this->dataDanToc =  $this->danhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('dan_toc', $request['dan_toc_id']);
        $this->dataQuocTich =  $this->danhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('quoc_tich', $request['quoc_tich_id']);
        $this->dataTinh = $this->hanhChinhRepository->getDataTinhById($request['tinh_thanh_pho_id']);
        $this->dataHuyen = $this->hanhChinhRepository->getDataHuyenById($request['tinh_thanh_pho_id'],$request['quan_huyen_id']);
        $this->dataXa = $this->hanhChinhRepository->getDataXaById($request['tinh_thanh_pho_id'],$request['quan_huyen_id'],$request['phuong_xa_id']);
        
        array_map(
            function ($k,$data) { 
                if (empty($data)){
                    //TODO - Logging Not found Data, set to OTHER ID
                    throw new \InvalidArgumentException($k." Not found");
                }
            },
            [
                'dataNgheNghiep','dataDanToc','dataQuocTich'
            ],
            [
                $this->dataNgheNghiep,
                $this->dataDanToc,
                $this->dataQuocTich
            ]
        );
        
        $this->dataNhomNguoiThan = new NhomNguoiThan($arrayRequest['loai_nguoi_than'], $arrayRequest['ten_nguoi_than'], $arrayRequest['dien_thoai_nguoi_than']);
        
        //set params benh_nhan 
        $benhNhanParams = $request->only(...$this->benhNhanKeys);
        $hsbaParams = $request->only(...$this->hsbaKeys);
        $hsbaKpParams = $request->only(...$this->hsbaKpKeys);
        $bhytParams = $request->only(...$this->bhytKeys);
        
        $bhytParams['image_url'] = $request->only('image_url_bhyt')['image_url_bhyt'];     
        $dieuTriParams = $request->only(...$this->dieuTriKeys);        
        $sttPhongKhamParams =  $request->only(...$this->sttPkKeys);
        $chuyenVienParams =  $request->only(...$this->chuyenVienKeys);
        $result = DB::transaction(function () use ($scan, $benhNhanParams, $hsbaParams, $hsbaKpParams, $bhytParams, $dieuTriParams, $sttPhongKhamParams,$chuyenVienParams) {
            try {
                // TODO - implement try catch log inside each function carefully
                $this->createBhyt($bhytParams)
                    ->checkOrCreateBenhNhan($scan,$benhNhanParams)
                    ->createHsbaKhamBenh($hsbaParams)
                    ->createHsbaKpKhamBenh($hsbaKpParams)
                    ->createChuyenVien($chuyenVienParams)
                    ->getDataYeucauKham()
                    ->getSttPhongKham($sttPhongKhamParams) //sothutuphongkham
                    ->createVienPhi()
                    ->updateHsba()
                    ->updateHsbaKp()
                    ->updateVienPhi()
                    ->createDieuTri()
                    ->createPhieuYLenh()
                    ->createYLenh()
                    ->pushToHsbaKpQueue();
                
                return $this->dataSttPk;
                
            } catch (\Exception $ex) {
                var_dump($ex->getMessage());
                echo "<br/>";
                var_dump($ex->getFile());
                echo "<br/>";
                var_dump($ex->getLine());die;
                throw $ex;
            }
        });
        $this->uploadInfoJson($arrayRequest);
        return $result;
    }
    
    private function createBhyt($params) {
        if($params['ms_bhyt'] != null && $params['tu_ngay'] != null && $params['den_ngay'] != null) {
            $dataBhyt = $params;
            $dataBhyt['id'] = $this->bhytRepository->createDataBhyt($params);
        } else {
            $dataBhyt['id'] = null;
            $dataBhyt['ms_bhyt'] = null;
        }
        $this->dataBhyt = $dataBhyt;
        return $this;
    }
    
    private function checkOrCreateBenhNhan($scan,$params) {
        $tenBenhNhanInHoa = mb_convert_case($params['ho_va_ten'], MB_CASE_UPPER, "UTF-8");
        $dataBenhNhan = $params;
        $dataBenhNhan['ho_va_ten'] = $tenBenhNhanInHoa;
        $dataBenhNhan['nghe_nghiep_id'] = ($this->dataNgheNghiep['gia_tri'])??null;
        $dataBenhNhan['dan_toc_id'] = $this->dataDanToc['gia_tri']??null;
        $dataBenhNhan['quoc_tich_id'] = $this->dataQuocTich['gia_tri']??null;
        $dataBenhNhan['nam_sinh'] =  str_limit($dataBenhNhan['ngay_sinh'], 4,'');// TODO - define constant
        $dataBenhNhan['nguoi_than'] = $this->dataNhomNguoiThan->toJsonEncoded();
        //$dataBenhNhan['thong_tin_chuyen_tuyen'] = !empty($dataBenhNhan['thong_tin_chuyen_tuyen']) ? json_encode($dataBenhNhan['thong_tin_chuyen_tuyen']) : null;
        $bhyt = $this->checkBhytFromScanner($scan);
        if ($bhyt['benh_nhan_id']) {
            $dataBenhNhan['id'] = $bhyt['benh_nhan_id'];
        } else {
            $dataBenhNhan['id'] =  $this->benhNhanRepository->createDataBenhNhan($dataBenhNhan);
        }
        $this->dataBenhNhan = $dataBenhNhan;
        return $this;
    }
    
    private function createHsbaKhamBenh($params) {
        $dataHsba = $params;
        $dataHsba['loai_benh_an'] = 24;// TODO - define constant
        $dataHsba['hinh_thuc_vao_vien'] = 2;// TODO - define constant
        $dataHsba['trang_thai_hsba'] = 0;// TODO - define constant
        $dataHsba['benh_nhan_id'] = $this->dataBenhNhan['id'];
        $dataHsba['ten_benh_nhan'] = $this->dataBenhNhan['ho_va_ten'];
        $dataHsba['ten_benh_nhan_khong_dau'] = Util::convertViToEn($this->dataBenhNhan['ho_va_ten']);
        $dataHsba['is_dang_ky_truoc'] = 0;// TODO - define constant
        $dataHsba['ten_nghe_nghiep'] = $this->dataNgheNghiep['dien_giai']??null;
        $dataHsba['ten_dan_toc'] = $this->dataDanToc['dien_giai']??null;
        $dataHsba['ten_quoc_tich'] = $this->dataQuocTich['dien_giai']??null;
        $dataHsba['nghe_nghiep_id'] = $this->dataNgheNghiep['gia_tri']??null;
        $dataHsba['dan_toc_id'] = $this->dataDanToc['gia_tri']??null;
        $dataHsba['quoc_tich_id'] = $this->dataQuocTich['gia_tri']??null;
        //chưa xử id
        $dataHsba['ten_tinh_thanh_pho'] = $this->dataTinh['ten_tinh']??null;
        $dataHsba['ten_quan_huyen'] = $this->dataHuyen['ten_huyen']??null;
        $dataHsba['ten_phuong_xa'] = $this->dataXa['ten_xa']??null;
        $dataHsba['nam_sinh'] =  $this->dataBenhNhan['nam_sinh'];
        $dataHsba['nguoi_than'] = $this->dataNhomNguoiThan->toJsonEncoded();
        $dataHsba['ngay_tao'] = Carbon::now()->toDateTimeString();
        //$dataHsba['thong_tin_chuyen_tuyen'] = !empty($dataHsba['thong_tin_chuyen_tuyen']) ? json_encode($dataHsba['thong_tin_chuyen_tuyen']) : null;
        //var_dump($dataHsba);
        $dataHsba['id'] = $this->hsbaRepository->createDataHsba($dataHsba);
        $this->dataHsba = $dataHsba;
        return $this;
    }
    
    private function createHsbaKpKhamBenh($params) {
        //set params hsba_khoa_phong
        $dataHsbaKp = $params;
        $dataHsbaKp['khoa_hien_tai'] = $this->dataHsba['khoa_id'];
        $dataHsbaKp['hsba_id'] = $this->dataHsba['id'];
        $dataHsbaKp['trang_thai'] = 0;// TODO - define constant
        $dataHsbaKp['loai_benh_an'] = 24;// TODO - define constant
        $dataHsbaKp['benh_nhan_id'] = $this->dataBenhNhan['id'];
        $dataHsbaKp['hinh_thuc_vao_vien_id'] = 2;// TODO - define constant
        $dataHsbaKp['bhyt_id'] = $this->dataBhyt['id'];
        $dataHsbaKp['thoi_gian_vao_vien'] = Carbon::now()->toDateTimeString();
        //insert hsba_khoa_phong
        $dataHsbaKp['id'] = $this->hsbaKhoaPhongRepository->createData($dataHsbaKp);
        $this->dataHsbaKp = $dataHsbaKp;
        return $this;
    }
    
    private function createChuyenVien($params) {
        //set params chuyen_vien
        //$dataChuyenVien = $params;
        if(count($params)>1){
            $dataChuyenVien['khoa_id'] = $params['khoa_id'];
            $dataChuyenVien['hsba_khoa_phong_id'] = $this->dataHsbaKp['id']?$this->dataHsbaKp['id']:null;
            $dataChuyenVien['benh_nhan_id'] = $this->dataBenhNhan['id']?$this->dataBenhNhan['id']:null;
            $dataChuyenVien['thoi_gian_chuyen_vien'] = isset($params['chuyen_tuyen_hoi'])?$params['chuyen_tuyen_hoi']:null;
            $dataChuyenVien['ma_benh_vien_chuyen_toi'] = isset($params['benh_vien_tuyen_duoi_code'])?$params['benh_vien_tuyen_duoi_code']:null;
            $dataChuyenVien['tinh_trang_nguoi_benh'] = isset($params['tinh_trang_chuyen_tuyen'])?$params['tinh_trang_chuyen_tuyen']:null;
            $dataChuyenVien['phuong_tien_van_chuyen'] = isset($params['phuong_tien_van_chuyen'])?$params['phuong_tien_van_chuyen']:null;
            $dataChuyenVien['nguoi_van_chuyen'] = isset($params['ten_nguoi_ho_tong'])?$params['ten_nguoi_ho_tong']:null;
            $dataChuyenVien['dau_hieu_lam_sang'] = isset($params['dau_hieu_lam_sang'])?$params['dau_hieu_lam_sang']:null;
            $dataChuyenVien['thuoc'] = isset($params['phuong_phap_thu_thuat'])?$params['phuong_phap_thu_thuat']:null;
            $dataChuyenVien['xet_nghiem'] = isset($params['ket_qua_xet_nghiem'])?$params['ket_qua_xet_nghiem']:null;
            $dataChuyenVien['huong_dieu_tri'] = isset($params['huong_dieu_tri'])?$params['huong_dieu_tri']:null;
            $dataChuyenVien['chan_doan'] = isset($params['chan_doan'])?$params['chan_doan']:null;
            $dataChuyenVien['ly_do_chuyen_vien_id'] = isset($params['ly_do_chuyen_tuyen'])?$params['ly_do_chuyen_tuyen']:null;
            $dataChuyenVien['chan_doan_tuyen_duoi_code'] = isset($params['chan_doan_tuyen_duoi_code'])?$params['chan_doan_tuyen_duoi_code']:null;
            $dataChuyenVien['chan_doan_tuyen_duoi_text'] = isset($params['chan_doan_tuyen_duoi'])?$params['chan_doan_tuyen_duoi']:null;
            //insert chuyen_vien
            $dataChuyenVien['id'] = $this->chuyenVienRepository->createData($dataChuyenVien);
            $this->dataChuyenVien = $dataChuyenVien;
            return $this;
        }
        else
            return $this;
    }    
    
    private function getSttPhongKham($params) {
        $sttPhongKhamParams = $params;
        $sttPhongKhamParams['benh_nhan_id'] = $this->dataBenhNhan['id'];
        $sttPhongKhamParams['ten_benh_nhan'] = $this->dataBenhNhan['ho_va_ten'];
        $sttPhongKhamParams['gioi_tinh_id'] = $this->dataBenhNhan['gioi_tinh_id'];
        $sttPhongKhamParams['ms_bhyt'] = $this->dataHsba['ms_bhyt'];
        $sttPhongKhamParams['yeu_cau_kham'] = $this->dataYeuCauKham['ten'];
        $sttPhongKhamParams['khoa_id'] = $this->dataHsba['khoa_id'];
        $sttPhongKhamParams['benh_vien_id'] = $this->dataHsba['benh_vien_id'];
        $sttPhongKhamParams['hsba_id'] = $this->dataHsba['id'];
        $sttPhongKhamParams['hsba_khoa_phong_id'] = $this->dataHsbaKp['id'];
        $dataSttPhongKham = $this->sttPhongKhamService->getSttPhongKham($sttPhongKhamParams);
        $this->dataSttPk = $dataSttPhongKham;
        return $this;
    }
    
    private function createVienPhi() {
        //set params vien_phi
        $dataVienPhi['loai_vien_phi'] = $this->dataHsbaKp['doi_tuong_benh_nhan'] == 1 ? 4 : 1;// TODO - define constant
        $dataVienPhi['trang_thai'] = 0;// TODO - define constant
        $dataVienPhi['khoa_id'] = $this->dataHsba['khoa_id'];
        $dataVienPhi['doi_tuong_benh_nhan'] = $this->dataHsbaKp['doi_tuong_benh_nhan'];
        $dataVienPhi['bhyt_id'] = $this->dataBhyt['id'];
        $dataVienPhi['benh_nhan_id'] = $this->dataBenhNhan['id'];
        $dataVienPhi['hsba_id'] = $this->dataHsba['id'];
        $dataVienPhi['trang_thai_thanh_toan_bh'] = 0;// TODO - define constant
        //insert vien_phi
        $dataVienPhi['id'] = $this->vienPhiRepository->createDataVienPhi($dataVienPhi);
        $this->dataVienPhi = $dataVienPhi;
        return $this;
    }
    
    private function createDieuTri() {
        //set params dieu_tri
        $dataDieuTri['hsba_khoa_phong_id'] = $this->dataHsbaKp['id'];
        $dataDieuTri['hsba_id'] = $this->dataHsba['id'];
        $dataDieuTri['khoa_id'] = $this->dataHsba['khoa_id'];
        $dataDieuTri['phong_id'] = $this->dataSttPk['phong_id'];
        $dataDieuTri['auth_users_id'] = $this->dataHsba['auth_users_id'];
        $dataDieuTri['benh_nhan_id'] =  $this->dataBenhNhan['id'];
        $dataDieuTri['ten_benh_nhan'] = $this->dataBenhNhan['ho_va_ten'];
        $dataDieuTri['nam_sinh'] = str_limit($this->dataBenhNhan['ngay_sinh'], 4,'');// TODO - define constant
        $dataDieuTri['gioi_tinh_id'] = $this->dataBenhNhan['gioi_tinh_id'];
        //insert dieu_tri
        $dataDieuTri['id'] = $this->dieuTriRepository->createDataDieuTri($dataDieuTri);
        $this->dataDieuTri = $dataDieuTri;
        return $this;
    }
    
    private function createPhieuYLenh() {
        //set params phieu_y_lenh
        $dataPhieuYLenh['benh_nhan_id'] = $this->dataBenhNhan['id'];
        $dataPhieuYLenh['vien_phi_id'] = $this->dataVienPhi['id'];
        $dataPhieuYLenh['hsba_id'] = $this->dataHsba['id'];
        $dataPhieuYLenh['dieu_tri_id'] = $this->dataDieuTri['id'];
        $dataPhieuYLenh['khoa_id'] = $this->dataHsba['khoa_id'];
        $dataPhieuYLenh['phong_id'] = $this->dataSttPk['phong_id'];
        $dataPhieuYLenh['auth_users_id'] = $this->dataHsba['auth_users_id'];
        $dataPhieuYLenh['loai_phieu_y_lenh'] = 2; // TODO - define constant
        $dataPhieuYLenh['trang_thai'] = 0; // TODO - define constant
        $dataPhieuYLenh['id'] = $this->phieuYLenhRepository->getPhieuYLenhId($dataPhieuYLenh);
        $this->dataPhieuYLenh = $dataPhieuYLenh;
        return $this;
    }
    
    private function createYLenh() {
        //tính bhyt, viện phí
        if($this->dataHsba['ms_bhyt']) {
            $input['ms_bhyt'] = $this->dataHsba['ms_bhyt'];
            $mucHuong = $this->vienPhiService->getMucHuong($input);
        } else {
            $mucHuong = 0;
        }
        $bhytTra = $mucHuong * (int)$this->dataYeuCauKham['gia_bhyt'];
        $vienPhi = (1 - $mucHuong) * (int)$this->dataYeuCauKham['gia_bhyt'] + (int)$this->dataYeuCauKham['gia'] - (int)$this->dataYeuCauKham['gia_bhyt'];
        
        //set params y_lenh
        $dataYLenh['vien_phi_id'] = $this->dataVienPhi['id'];
        $dataYLenh['phieu_y_lenh_id'] = $this->dataPhieuYLenh['id'];
        $dataYLenh['doi_tuong_benh_nhan'] = $this->dataHsbaKp['doi_tuong_benh_nhan'];
        $dataYLenh['khoa_id'] = $this->dataHsba['khoa_id'];
        $dataYLenh['phong_id'] = $this->dataSttPk['phong_id'];
        $dataYLenh['ma'] = $this->dataYeuCauKham['ma'];
        $dataYLenh['ten'] = $this->dataYeuCauKham['ten'];
        $dataYLenh['ten_bhyt'] = $this->dataYeuCauKham['ten_bhyt'];
        $dataYLenh['ten_nuoc_ngoai'] = $this->dataYeuCauKham['ten_nuoc_ngoai'];
        $dataYLenh['trang_thai'] = 0; // TODO - define constant
        $dataYLenh['gia'] = (double)$this->dataYeuCauKham['gia'];
        $dataYLenh['gia_bhyt'] = (double)$this->dataYeuCauKham['gia_bhyt'];
        $dataYLenh['gia_nuoc_ngoai'] = (double)$this->dataYeuCauKham['gia_nuoc_ngoai'];
        $dataYLenh['loai_y_lenh'] = 1; // TODO - define constant
        $dataYLenh['thoi_gian_chi_dinh'] = Carbon::now()->toDateTimeString();
        $dataYLenh['muc_huong'] = $mucHuong;
        $dataYLenh['bhyt_tra'] = $bhytTra;
        $dataYLenh['vien_phi'] = $vienPhi;
        $dataYLenh['id'] = $this->yLenhRepository->createDataYLenh($dataYLenh);
        $this->dataYLenh = $dataYLenh;
        return $this;
    }
    
    private function checkBhytFromScanner($scan) {
        $msBhyt = trim($scan['scan']);
        if($this->isBHYTNumber($msBhyt))//thẻ bhyt
        {
            $checkedBhyt = $this->bhytRepository->checkMaSoBhyt($msBhyt);
        } else {
            return null;
        }
    }
    
    private function isBHYTNumber($value) {
        return strlen($value) == 15;
    }
    
    private function pushToHsbaKpQueue() {
        
        $benhVienId = $this->dataHsba['benh_vien_id'];
        $khoaId = $this->dataHsba['khoa_id'];
        $phongId = $this->dataSttPk['phong_id'];
        $ngayVaoVien = Carbon::now()->toDateString();
        $this->hsbaKhoaPhongService ->setQueueAttribute($benhVienId, $khoaId, $phongId, $ngayVaoVien)
                                    ->setQueueBody([
                                            'benh_vien_id' => $this->dataHsba['benh_vien_id'],
                                            'hsba_id' => $this->dataHsba['id'], 
                                            'hsba_khoa_phong_id' => $this->dataHsbaKp['id'], 
                                            'ten_benh_nhan' => $this->dataBenhNhan['ho_va_ten'], 
                                            'nam_sinh' => $this->dataBenhNhan['nam_sinh'], 
                                            'ms_bhyt' => $this->dataBhyt['ms_bhyt'], 
                                            'trang_thai_hsba' => $this->dataHsba['trang_thai_hsba'],
                                            'ngay_tao' => $this->dataHsba['ngay_tao'], // Modify repository
                                            'ngay_ra_vien' => '', // Modify repository
                                            'thoi_gian_vao_vien' => $this->dataHsbaKp['thoi_gian_vao_vien'], 
                                            'thoi_gian_ra_vien' => '',
                                            'trang_thai_cls' => '', 
                                            'ten_trang_thai_cls' => '',
                                            'trang_thai' => $this->dataHsbaKp['trang_thai'], 
                                            'ten_trang_thai' => '' // TODO - get Ten Trang Thai CLS
                                        ])
                                    ->pushToQueue();
        return $this;
    }    
    
    // private function setDataTHX($params) {
    //     $this->dataTenTHX = Util::getDataFromGooglePlace($this->dataTHX);
    //     $this->dataTinh = $this->hanhChinhRepository->getDataTinh(mb_convert_case($this->dataTenTHX['ten_tinh_thanh_pho'], MB_CASE_UPPER, "UTF-8"));
    //     $this->dataHuyen = $this->hanhChinhRepository->getDataHuyen($this->dataTinh['ma_tinh'], mb_convert_case($this->dataTenTHX['ten_quan_huyen'], MB_CASE_UPPER, "UTF-8"));
    //     $this->dataXa = $this->hanhChinhRepository->getDataXa($params['tinh_thanh_pho_id'], $params['quan_huyen_id'], $params['phuong_xa_id']);
    // }
    
    private function getDataYeucauKham(){
         $this->dataYeuCauKham = $this->danhMucDichVuRepository->getDataDanhMucDichVuById($this->dataHsbaKp['yeu_cau_kham_id']);
         return $this;
    }
    
    private function updateHsba(){
        //update phong_id từ stt_phong_kham
        //$this->hsbaRepository->updateHsba($idHsba, $thxData);
        $this->hsbaRepository->updateHsba($this->dataHsba['id'], ['phong_id' => $this->dataSttPk['phong_id']]);
        return $this;
    }
    
    private function updateHsbaKp(){
        $this->hsbaKhoaPhongRepository->update($this->dataHsbaKp['id'], ['phong_hien_tai' => $this->dataSttPk['phong_id'], 'vien_phi_id' => $this->dataVienPhi['id']]);
        return $this;
    }
    
    private function updateVienPhi(){
        $this->vienPhiRepository->updateVienPhi($this->dataVienPhi['id'], ['phong_id' => $this->dataSttPk['phong_id']]);
        return $this;
    }
    
    private function uploadInfoJson($arrayRequest) {
        $dataBenhVienThietLap = $this->hsbaKhoaPhongService->getBenhVienThietLap($arrayRequest['benh_vien_id']);
        $s3 = new AwsS3($dataBenhVienThietLap['bucket']);
        $json_data = json_encode($arrayRequest);
        file_put_contents('myfile.json', $json_data);
        
        $pathName = public_path('myfile.json');
        $result = $s3->putObject('dang-ky-kham-benh/' . time() . '_myfile.json', $pathName, 'application/json');
        unlink($pathName);
    }
    
    private function getMucHuong()
    {
        
    }
}
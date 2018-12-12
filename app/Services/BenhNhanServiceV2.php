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
use Carbon\Carbon;

//Value objects
use App\Models\ValueObjects\NhomNguoiThan;

use Validator;

class BenhNhanServiceV2{
    
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
    private $dataTHX = null;
    private $dataTenTHX = [];
    private $dataNhomNguoiThan = null;
    
    private $dataQueue = [
        'message_attribute' => [],
        'message_body' => []
    ];
    private $dataLog = [];
    
    private $benhNhanKeys = [
        'benh_nhan_id', 'ho_va_ten', 'ngay_sinh', 'gioi_tinh_id'
        , 'so_nha', 'duong_thon', 'noi_lam_viec'
        , 'url_hinh_anh', 'dien_thoai_benh_nhan', 'email_benh_nhan', 'dia_chi_lien_he'
        , 'tinh_thanh_pho_id' , 'quan_huyen_id' , 'phuong_xa_id', 'thong_tin_chuyen_tuyen'
    ];
    
    private $hsbaKeys = [
        'auth_users_id', 'khoa_id'
        , 'ngay_sinh', 'gioi_tinh_id'
        , 'so_nha', 'duong_thon', 'noi_lam_viec'
        , 'url_hinh_anh', 'dien_thoai_benh_nhan', 'email_benh_nhan', 'dia_chi_lien_he'
        , 'ms_bhyt', 'benh_vien_id'
        , 'tinh_thanh_pho_id' , 'quan_huyen_id' , 'phuong_xa_id', 'thong_tin_chuyen_tuyen'
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
        
        $this->dataNgheNghiep = $this->danhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('nghe_nghiep', $request['nghe_nghiep_id']);
        $this->dataDanToc =  $this->danhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('dan_toc', $request['dan_toc_id']);
        $this->dataQuocTich =  $this->danhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('quoc_tich', $request['quoc_tich_id']);
        
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
        
        // $this->dataTHX = !empty($request['thx_gplace_json']) ??null;
        // if(!empty($this->dataTHX))
        // {
        //     $this->setDataTHX($request);
        // }
        
        $this->dataNhomNguoiThan = new NhomNguoiThan($arrayRequest['loai_nguoi_than'], $arrayRequest['ten_nguoi_than'], $arrayRequest['dien_thoai_nguoi_than']);
        
        
        //set params benh_nhan 
        $benhNhanParams = $request->only(...$this->benhNhanKeys);
        $hsbaParams = $request->only(...$this->hsbaKeys);
        $hsbaKpParams = $request->only(...$this->hsbaKpKeys);
        $bhytParams = $request->only(...$this->bhytKeys);
        
        $bhytParams['image_url'] = $request->only('image_url_bhyt')['image_url_bhyt'];     
        $dieuTriParams = $request ->only (...$this->dieuTriKeys);        
        $sttPhongKhamParams =  $request ->only (...$this->sttPkKeys);
        $result = DB::transaction(function () use ($scan, $benhNhanParams, $hsbaParams, $hsbaKpParams, $bhytParams, $dieuTriParams, $sttPhongKhamParams) {
            try {
                
                $this->dataBhyt = $this->createBhyt($bhytParams);
                $this->dataBenhNhan = $this->checkOrCreateBenhNhan($scan,$benhNhanParams);
                $this->dataHsba = $this->createHsbaKhamBenh($hsbaParams);
                $this->dataHsbaKp = $this->createHsbaKpKhamBenh($hsbaKpParams);
                $this->dataYeuCauKham = $this->danhMucDichVuRepository->getDataDanhMucDichVuById($this->dataHsbaKp['yeu_cau_kham_id']);
                //sothutuphongkham
                $this->dataSttPk = $this->getSttPhongKham($sttPhongKhamParams);
                
                $this->dataVienPhi = $this->createVienPhi();
                $phongId = $this->dataSttPk['phong_id'];
                //update phong_id từ stt_phong_kham
                //$this->hsbaRepository->updateHsba($idHsba, $thxData);
                $this->hsbaRepository->updateHsba($this->dataHsba['id'], ['phong_id' => $phongId, 'thx_gplace_json' => $this->dataTHX]);
                $this->hsbaKhoaPhongRepository->update($this->dataHsbaKp['id'], ['phong_hien_tai' => $phongId, 'vien_phi_id' => $this->dataVienPhi['id']]);
                $this->vienPhiRepository->updateVienPhi($this->dataVienPhi['id'], ['phong_id' => $phongId]);
                
                $this->dataDieuTri = $this->createDieuTri();
                
                $this->dataPhieuYLenh = $this->createPhieuYLenh();
                $this->dataYLenh = $this->createYLenh();
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
        
        return $result;
    }
    
    private function setQueueAttribute() {

        $ngayVaoVien = Carbon::now()->isoFormat('YYYY-MM-DD');
        $messageAttributes = [
            'benh_vien_id' => ['DataType' => "Number",
                                'StringValue' => $this->dataHsba['benh_vien_id']
                            ],
            'khoa_id' => ['DataType' => "Number",
                                'StringValue' => $this->dataHsba['khoa_id']
                            ],
            'phong_id' => ['DataType' => "Number",
                                'StringValue' => $this->dataSttPk['phong_id']
                            ],
            'ngay_vao_vien' => ['DataType' => "String",
                                'StringValue' => $ngayVaoVien
                            ]                
        ];
        $this->dataQueue['message_attributes'] = $messageAttributes;

    }
    
    private function setQueueBody() {
        $messageBody = [
            'benh_vien_id' => $this->dataHsba['benh_vien_id'],
            'hsba_id' => $this->dataHsba['hsba_id'], 
            'hsba_khoa_phong_id' => $this->dataHsbaKp['id'], 
            'ten_benh_nhan' => $this->dataBenhNhan['ho_va_ten'], 
            'nam_sinh' => $this->dataBenhNhan['nam_sinh'], 
            'ms_bhyt' => $this->dataBhyt['ms_bhyt'], 
            'trang_thai_hsba' => $this->dataHsba['trang_thai_hsba'],
            'ngay_tao' => $this->dataHsba['ngay_tao'], // Modify repository
            'ngay_ra_vien' => $this->dataHsba['ngay_ra_vien'], // Modify repository
            'thoi_gian_vao_vien' => $this->dataHsbaKp['thoi_gian_vao_vien'], 
            'thoi_gian_ra_vien' => '',
            'trang_thai_cls' => '', 
            'ten_trang_thai_cls' => '', // TODO - get Ten Trang Thai CLS
            'trang_thai' => $this->dataHsbaKp['trang_thai'], 
            'ten_trang_thai' => '' // TODO - get Ten Trang Thai CLS
        ];
        $this->dataQueue['message_body'] = $messageBody;
    }
    
    private function pushToQueue($messageAttributes,$messageBody) {
        $this->sqsRepo->push(
                $messageAttributes,$messageBody
            );
    }
    
    private function createBhyt($params) {
        if($params['ms_bhyt'] != null && $params['tu_ngay'] != null && $params['den_ngay'] != null) {
            $dataBhyt = $params;
            $dataBhyt['id'] = $this->bhytRepository->createDataBhyt($params);
        } else {
            $dataBhyt['id'] = null; 
        }
        return $dataBhyt;
    }
    
    private function checkOrCreateBenhNhan($scan,$params) {
         
        $tenBenhNhanInHoa = mb_convert_case($params['ho_va_ten'], MB_CASE_UPPER, "UTF-8");
        
        $dataBenhNhan = $params;
                
        $dataBenhNhan['ho_va_ten'] = $tenBenhNhanInHoa;
        $dataBenhNhan['nghe_nghiep_id'] = ($this->dataNgheNghiep['gia_tri'])??null;
        $dataBenhNhan['dan_toc_id'] = $this->dataDanToc['gia_tri']??null;
        $dataBenhNhan['quoc_tich_id'] = $this->dataQuocTich['gia_tri']??null;
        $dataBenhNhan['tinh_thanh_pho_id'] = isset($this->dataTinh['ma_tinh'])??null;
        $dataBenhNhan['quan_huyen_id'] = isset($this->dataHuyen['ma_huyen'])??null;
        $dataBenhNhan['phuong_xa_id'] = isset($this->dataXa['ma_xa'])??null;
        $dataBenhNhan['nam_sinh'] =  str_limit($dataBenhNhan['ngay_sinh'], 4,'');// TODO - define constant
        //$dataBenhNhan['nguoi_than'] = $this->dataNhomNguoiThan->toJsonEncoded();
        $dataBenhNhan['nguoi_than'] = '';
        $dataBenhNhan['thong_tin_chuyen_tuyen'] = json_encode($dataBenhNhan['thong_tin_chuyen_tuyen']);
        $bhyt = $this->checkBhytFromScanner($scan);
        if ($bhyt['benh_nhan_id']) {
            $dataBenhNhan['id'] = $bhyt['benh_nhan_id'];
        } else {
            $dataBenhNhan['id'] =  $this->benhNhanRepository->createDataBenhNhan($dataBenhNhan);
        }
        
        return $dataBenhNhan;
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
        $dataHsba['tinh_thanh_pho_id'] = $this->dataTinh['ma_tinh']?? null;
        $dataHsba['quan_huyen_id'] = $this->dataHuyen['ma_huyen'] ??null;
        $dataHsba['phuong_xa_id'] = $this->dataXa['ma_xa'] ??null;
        $dataHsba['nam_sinh'] =  $this->dataBenhNhan['nam_sinh'];
        //$dataHsba['nguoi_than'] = $this->dataNhomNguoiThan->toJsonEncoded();
        $dataHsba['nguoi_than'] = '';
        $dataHsba['ngay_tao'] = Carbon::now()->toDateTimeString();
        $dataHsba['thong_tin_chuyen_tuyen'] = json_encode($dataHsba['thong_tin_chuyen_tuyen']);
        $dataHsba['id'] = $this->hsbaRepository->createDataHsba($dataHsba);
        return $dataHsba;
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
        return $dataHsbaKp;
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
        return $dataSttPhongKham;
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
        return $dataVienPhi;
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
        return $dataDieuTri;
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
        $dataPhieuYLenh['id'] = $this->phieuYLenhRepository->createDataPhieuYLenh($dataPhieuYLenh);
        return $dataPhieuYLenh;
    }
    
    private function createYLenh() {
        //set params y_lenh
        $dataYLenh['vien_phi_id'] = $this->dataVienPhi['id'];
        $dataYLenh['phieu_y_lenh_id'] = $this->dataPhieuYLenh['id'];
        $dataYLenh['doi_tuong_benh_nhan'] = $this->dataHsbaKp['doi_tuong_benh_nhan'];
        $dataYLenh['khoa_id'] = $this->dataHsba['khoa_id'];
        $dataYLenh['phong_id'] = $this->dataSttPk['phong_id'];
        $dataYLenh['ma'] = $this->dataYeuCauKham['ma'];
        $dataYLenh['ten'] = $this->dataYeuCauKham['ten'];
        $dataYLenh['ten_nhan_dan'] = $this->dataYeuCauKham['ten_nhan_dan'];
        $dataYLenh['ten_bhyt'] = $this->dataYeuCauKham['ten_bhyt'];
        $dataYLenh['ten_nuoc_ngoai'] = $this->dataYeuCauKham['ten_nuoc_ngoai'];
        $dataYLenh['trang_thai'] = 0; // TODO - define constant
        $dataYLenh['gia'] = (double)$this->dataYeuCauKham['gia'];
        $dataYLenh['gia_nhan_dan'] = (double)$this->dataYeuCauKham['gia_nhan_dan'];
        $dataYLenh['gia_bhyt'] = (double)$this->dataYeuCauKham['gia_bhyt'];
        $dataYLenh['gia_nuoc_ngoai'] = (double)$this->dataYeuCauKham['gia_nuoc_ngoai'];
        $dataYLenh['id']  = $this->yLenhRepository->createDataYLenh($dataYLenh);
        return $dataYLenh;
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
    
    private function setDataTHX($params) {
        $this->dataTenTHX = Util::getDataFromGooglePlace($this->dataTHX);
        $this->dataTinh = $this->hanhChinhRepository->getDataTinh(mb_convert_case($this->dataTenTHX['ten_tinh_thanh_pho'], MB_CASE_UPPER, "UTF-8"));
        $this->dataHuyen = $this->hanhChinhRepository->getDataHuyen($this->dataTinh['ma_tinh'], mb_convert_case($this->dataTenTHX['ten_quan_huyen'], MB_CASE_UPPER, "UTF-8"));
        $this->dataXa = $this->hanhChinhRepository->getDataXa($params['tinh_thanh_pho_id'], $params['quan_huyen_id'], $params['phuong_xa_id']);
    }
}
<?php
namespace App\Services;
use DB;
use App\Repositories\DieuTri\DieuTriRepository;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use App\Repositories\HsbaKhoaPhong\HsbaKhoaPhongRepository;
use App\Repositories\PhongRepository;
use App\Services\SttPhongKhamService;

class DieuTriService
{
    const KET_THUC_DIEU_TRI = 99;
    const CHO_DIEU_TRI = 0;
    const DANG_DIEU_TRI = 2;
    const CHUYEN_KHOA = 8;
    const NHAN_TU_KKB = 2;
    const PHONG_DIEU_TRI_NOI_TRU = 3;
    const PHONG_DIEU_TRI_NGOAI_TRU = 9;
    const PHONG_HANH_CHINH = 1;
    const BENH_AN_KHAM_BENH = 24;
    
    public function __construct
    (
        DieuTriRepository $dieuTriRepository, 
        HsbaKhoaPhongRepository $hsbaKhoaPhongRepository, 
        PhongRepository $phongRepository,
        SttPhongKhamService $sttPhongKhamService
    )
    {
        $this->dieuTriRepository = $dieuTriRepository;
        $this->hsbaKhoaPhongRepository = $hsbaKhoaPhongRepository;
        $this->phongRepository = $phongRepository;
        $this->sttPhongKhamService = $sttPhongKhamService;
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
                $input = array_except($input, ['hsba_khoa_phong_id']);
                $input = array_except($input, ['thoi_gian_chi_dinh']);
                $input = array_except($input, ['khoa_id']);
                $input = array_except($input, ['phong_id']);
                $this->hsbaKhoaPhongRepository->updateHsbaKhoaPhong($dieuTriParams['hsba_khoa_phong_id'], $input);
            } catch (\Exception $ex) {
                 throw $ex;
            }
        });
        return $result;
    }
    
    public function createChuyenPhong(array $request)
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
                $hsbaKp = $this->hsbaKhoaPhongRepository->getHsbaKhoaPhongById($request['hsba_khoa_phong_id']);
                if($hsbaKp == null) 
                    return 'không tìm thấy hsba_khoa_phong';
                if($hsbaKp['trang_thai'] == self::KET_THUC_DIEU_TRI)
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
                        $this->hsbaKhoaPhongRepository->updateHsbaKhoaPhong($hsbaKp['id'], $hsbaKpParams);
                        return $data;
                    break;    
                    default://2.2 chuyển khoa
                        //update hsba_khoa_phong hiện tại
                        $this->updateHsbaKhoaPhongByXuTri($hsbaKp['id'], $request, self::KET_THUC_DIEU_TRI, self::CHUYEN_KHOA); //99: kết thúc điều trị; 8: chuyển khoa
                        //tạo hsba_khoa_phong 
                        $hsbaKpParams = null;
                        //$hsbaKpParams['auth_users_id'] = $request['auth_users_id'];
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
                        $hsbaKpParams['trang_thai'] = self::CHO_DIEU_TRI; //0: chờ điều trị
                        $hsbaKpParams['hsba_id'] = $hsbaKp['hsba_id'];
                        $hsbaKpParams['benh_nhan_id'] = $hsbaKp['benh_nhan_id'];
                        $hsbaKpParams['hinh_thuc_vao_vien_id'] = self::NHAN_TU_KKB; //2: nhận từ khoa khám bệnh
                        $hsbaKpParams['vien_phi_id'] = $hsbaKp['vien_phi_id'];
                        $hsbaKpParams['bhyt_id'] = $hsbaKp['bhyt_id'];
                        //kiểm tra phòng chuyển đến có phải là phòng điều trị -> nếu đúng -> lấy trạng thái = 2: đang điều trị ngược lại 0: đang chờ điều trị
                        $hsbaKpParams['trang_thai'] = $phong->loai_phong == self::PHONG_DIEU_TRI_NOI_TRU || $phong->loai_phong == self::PHONG_DIEU_TRI_NGOAI_TRU ? self::DANG_DIEU_TRI : self::CHO_DIEU_TRI; 
                        $idHsbaKp = $this->hsbaKhoaPhongRepository->createDataHsbaKhoaPhong($hsbaKpParams);
                        return 'OK';
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
        $input = array_except($input, ['hsba_khoa_phong_id', 'khoa_chuyen_den', 'phong_chuyen_den', 'ten_benh_nhan', 'nam_sinh',
                                        'xu_tri', 'giai_phau_benh', 'loai_stt', 'gioi_tinh_id', 'stt_don_tiep_id', 'ms_bhyt']);
        $this->hsbaKhoaPhongRepository->updateHsbaKhoaPhong($hsbaKp, $input);
    }
}
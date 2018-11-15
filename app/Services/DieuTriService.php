<?php
namespace App\Services;
use DB;
use App\Repositories\DieuTri\DieuTriRepository;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use App\Repositories\HsbaKhoaPhong\HsbaKhoaPhongRepository;

class DieuTriService
{
    public function __construct(DieuTriRepository $dieuTriRepository, HsbaKhoaPhongRepository $hsbaKhoaPhongRepository)
    {
        $this->dieuTriRepository = $dieuTriRepository;
        $this->hsbaKhoaPhongRepository = $hsbaKhoaPhongRepository;
    }
    
    public function updateInfoDieuTri(Request $request)
    {
        //Phiều điều trị của khoa khám bệnh đc tạo thời điểm đăng ký khám bệnh
        //1 lấy phiếu điều trị dựa vào hsba_khoa_phong_id
        //2. update thông tin phiếu điều trị
        $result = DB::transaction(function () use ($request) {
            try {
                $data = $this->dieuTriRepository->getDieuTriByHsba_Kp($request['hsba_khoa_phong_id'], $request['khoa_id'], $request['phong_id']); 
                $dieuTriParams['auth_users_id'] = $request['$request'];
                $dieuTriParams['thoi_gian_chi_dinh'] = Carbon::now()->toDateTimeString();
                $dieuTriParams['kham_toan_than'] = $request['kham_toan_than'];
                $dieuTriParams['kham_bo_phan'] = $request['kham_bo_phan'];
                $dieuTriParams['ket_qua_can_lam_san'] = $request['ket_qua_can_lam_san'];
                $dieuTriParams['cd_icd10_code'] = $request['cd_icd10_code'];
                $dieuTriParams['cd_icd10_text'] = $request['cd_icd10_text'];
                $dieuTriParams['xu_ly'] = $request['xu_ly'];
                $dieuTriParams['mach'] = $request['mach'];
                $dieuTriParams['nhiet_do'] = $request['nhiet_do'];
                $dieuTriParams['huyet_ap_tren'] = $request['huyet_ap_tren'];
                $dieuTriParams['huyet_ap_duoi'] = $request['huyet_ap_duoi'];
                $dieuTriParams['nhip_tho'] = $request['nhip_tho'];
                $dieuTriParams['can_nang'] = $request['can_nang'];
                $dieuTriParams['chieu_cao'] = $request['chieu_cao'];
                $dieuTriParams['sp_o2'] = $request['sp_o2'];
                $dieuTriParams['thi_luc_mat_trai'] = $request['thi_luc_mat_trai'];
                $dieuTriParams['thi_luc_mat_phai'] = $request['thi_luc_mat_phai'];
                $dieuTriParams['kl_thi_luc_mat_trai'] = $request['kl_thi_luc_mat_trai'];
                $dieuTriParams['kl_thi_luc_mat_phai'] = $request['kl_thi_luc_mat_phai'];
                $dieuTriParams['nhan_ap_mat_trai'] = $request['nhan_ap_mat_trai'];
                $dieuTriParams['nhan_ap_mat_phai'] = $request['nhan_ap_mat_phai'];
                $id = $this->dieuTriRepository->updateDieuTri($data['id'], $dieuTriParams);
            } catch (\Exception $ex) {
                 throw $ex;
            }
        });
        
        return $result;
    }
    
    public function createChuyenPhong($request)
    {
        //1. tìm khoa, phòng hiện tại của bệnh nhân
        //1.1 viện phí ?
        //2. so sánh khoa hiện tại và khoa chuyển tới
        //2.1 so sánh khoa hiện tại và khoa chuyển tới -> khoa = nhau chính là chuyển phòng -> chuyển phòng khám -> tạo stt_pk -> update khoa, phòng hiện tại
        //2.2 so sánh khoa hiện tại và khoa chuyển tới -> khác khoa chính là chuyển khoa -> chuyển khoa (chuyển bn này vào hành chính khoa) (chưa tính tới TH trả về phòng khám)
        $result = DB::transaction(function () use ($request) {
            $khoaChuyenDen = $request['khoa_chuyen_den'];
            $phongChuyenDen = $request['phong_chuyen_den'];
            try {
                //1
                $hsbaKp = $this->hsbaKhoaPhongRepository->getHsbaKhoaPhongById($request['hsba_kp_id']);
                if($hsbaKp == null) 
                    return 'không tìm thấy hsba_khoa_phong';
                if($hsbaKp['trang_thai'] = 99)
                    return 'hsba_khoa_phong này đã đóng';
                //1.1
                //viện phí ?
                $khoaHienTai = $hsbaKp['khoa_hien_tai'];
                $phongHienTai = $hsbaKp['phong_hien_tai'];
                switch ($khoaHienTai) {
                    case $khoaChuyenDen ://2.1 chuyển phòng
                        //tạo phiếu điều trị đối vs phòng chuyển đến
                        $dieuTriParams['hsba_khoa_phong_id'] = $hsbaKp['id'];
                        $dieuTriParams['hsba_id'] = $hsbaKp['hsba_id'];
                        $dieuTriParams['khoa_id'] = $khoaHienTai;
                        $dieuTriParams['phong_id'] = $phongChuyenDen;
                        $dieuTriParams['auth_users_id'] = $request['auth_users_id'];
                        $dieuTriParams['benh_nhan_id'] = $hsbaKp['benh_nhan_id'];;
                        $dieuTriParams['ten_benh_nhan'] = $hsbaKp['ten_benh_nhan'];
                        $dieuTriParams['nam_sinh'] = $request['nam_sinh'];
                        $dieuTriParams['gioi_tinh_id'] = $benhNhanParams['gioi_tinh_id'];
                        $idDieuTri = $this->dieuTriRepository->createDataDieuTri($dieuTriParams);
                        //update phong_hien_tai chuyển tới của hsba_khoa_phong hiện tại 
                        $hsbaKpParams['phong_hien_tai'] = $phongHienTai;
                        $this->hsbaKhoaPhongRepository->updateHsbaKhoaPhong($hsbaKp['id'], $hsbaKpParams['phong_hien_tai']);
                        //thêm stt_phong_kham
                        $sttPhongKhamParams =  $request ->only ('loai_stt', 'ma_nhom', 'stt_don_tiep_id');
                        $sttPhongKhamParams['benh_nhan_id'] = $hsbaKp['benh_nhan_id'];
                        $sttPhongKhamParams['ten_benh_nhan'] = $hsbaKp['ten_benh_nhan'];
                        $sttPhongKhamParams['gioi_tinh_id'] = $hsbaKp['gioi_tinh_id'];
                        $sttPhongKhamParams['ms_bhyt'] = $hsbaKp['ms_bhyt'];
                        $sttPhongKhamParams['yeu_cau_kham'] = $hsbaKp['yeu_cau_kham'];
                        $sttPhongKhamParams['khoa_id'] = $khoaChuyenDen;
                        $sttPhongKhamParams['benh_vien_id'] = $hsbaKp['benh_vien_id'];
                        $sttPhongKhamParams['hsba_id'] = $hsbaKp['hsba_id'];
                        $sttPhongKhamParams['hsba_khoa_phong_id'] = $hsbaKp['id'];
                        $data = $this->sttPhongKhamService->getSttPhongKham($sttPhongKhamParams);
                        return $data;
                    break;    
                default://2.2 chuyển khoa
                        //update hsba_khoa_phong hiện tại
                        //$request form
                        $hsbaKpParams = null;
                        $hsbaKpParams['cdtd_icd10_text'] = $request['cdtd_icd10_text'] == $hsbaKp['cdtd_icd10_text'] ? $hsbaKp['cdtd_icd10_text'] : $request['cdtd_icd10_text'];
                        $hsbaKpParams['cdtd_icd10_code'] = $request['cdtd_icd10_code'] == $hsbaKp['cdtd_icd10_code'] ? $hsbaKp['cdtd_icd10_code'] : $request['cdtd_icd10_code'];
                        $hsbaKpParams['cdvk_icd10_text'] = $request['cdvk_icd10_text'] == $hsbaKp['cdvk_icd10_text'] ? $hsbaKp['cdvk_icd10_text'] : $request['cdvk_icd10_text'];
                        $hsbaKpParams['cdvk_icd10_code'] = $request['cdvk_icd10_code'] == $hsbaKp['cdvk_icd10_code'] ? $hsbaKp['cdvk_icd10_code'] : $request['cdvk_icd10_code'];
                        $hsbaKpParams['isthuthuat'] = $request['isthuthuat'] == $hsbaKp['isthuthuat'] ? $hsbaKp['isthuthuat'] : $request['isthuthuat'];
                        $hsbaKpParams['isphauthuat'] = $request['isphauthuat'] == $hsbaKp['isphauthuat'] ? $hsbaKp['isphauthuat'] : $request['isphauthuat'];
                        $hsbaKpParams['istaibien'] = $request['istaibien'] == $hsbaKp['istaibien'] ? $hsbaKp['istaibien'] :  $request['istaibien'];
                        $hsbaKpParams['isbienchung'] = $request['isbienchung'] == $hsbaKp['isbienchung'] ? $hsbaKp['isbienchung'] : $request['isbienchung'];
                        $hsbaKpParams['cdrv_icd10_text'] = $request['cdrv_icd10_text'] == $hsbaKp['cdrv_icd10_text'] ? $hsbaKp['cdrv_icd10_text'] : $request['cdrv_icd10_text'];
                        $hsbaKpParams['cdrv_icd10_code'] = $request['cdrv_icd10_code'] == $hsbaKp['cdrv_icd10_code'] ? $hsbaKp['cdrv_icd10_code'] : $request['cdrv_icd10_code'];
                        $hsbaKpParams['cdrv_kt_icd10_text'] = $request['cdrv_kt_icd10_text'] == $hsbaKp['cdrv_kt_icd10_text'] ? $hsbaKp['cdrv_kt_icd10_text'] : $request['cdrv_kt_icd10_text'];
                        $hsbaKpParams['cdrv_kt_icd10_code'] = $request['cdrv_kt_icd10_code'] == $hsbaKp['cdrv_kt_icd10_code'] ? $hsbaKp['cdrv_kt_icd10_code'] : $request['cdrv_kt_icd10_code'];
                        $hsbaKpParams['ket_qua_dieu_tri'] = $request['ket_qua_dieu_tri'] == $hsbaKp['ket_qua_dieu_tri'] ? $hsbaKp['ket_qua_dieu_tri'] : $request['ket_qua_dieu_tri'];
                        $hsbaKpParams['xu_tri_kham_benh'] = $request['xu_tri_kham_benh'] == $hsbaKp['xu_tri_kham_benh'] ? $hsbaKp['xu_tri_kham_benh'] : $request['xu_tri_kham_benh'];
                        $hsbaKpParams['giai_phau_benh_id'] = $request['giai_phau_benh_id'] == $hsbaKp['giai_phau_benh_id'] ? $hsbaKp['giai_phau_benh_id'] : $request['giai_phau_benh_id'];
                        $hsbaKpParams['trang_thai'] = 99; //kết thúc điều trị
                        $hsbaKpParams['hinh_thuc_ra_vien'] = 8;//chuyển khoa
                        $this->hsbaKhoaPhongRepository->updateHsbaKhoaPhong($hsbaKp['id'], $hsbaKpParams);
                        //tạo hsba_khoa_phong 
                        $hsbaKpParams = null;
                        $hsbaKpParams['auth_users_id'] = $request['auth_users_id'];
                        $hsbaKpParams['doi_tuong_benh_nhan'] = $hsbaKp['doi_tuong_benh_nhan'];
                        $hsbaKpParams['yeu_cau_kham_id'] = $hsbaKp['yeu_cau_kham_id'];
                        $hsbaKpParams['cdtd_icd10_text'] = $request['cdtd_icd10_text'] == $hsbaKp['cdtd_icd10_text'] ? $hsbaKp['cdtd_icd10_text'] : $request['cdtd_icd10_text'];
                        $hsbaKpParams['cdtd_icd10_code'] = $request['cdtd_icd10_code'] == $hsbaKp['cdtd_icd10_code'] ? $hsbaKp['cdtd_icd10_code'] : $request['cdtd_icd10_code'];
                        $hsbaKpParams['benh_vien_id'] = $hsbaKp['benh_vien_id'];
                        $hsbaKpParams['khoa_hien_tai'] = $khoaChuyenDen;
                        $hsbaKpParams['phong_hien_tai'] = $phongChuyenDen;
                        $hsbaKpParams['trang_thai'] = 0;
                        $hsbaKpParams['hsba_id'] = $hsbaKp['hsba_id'];
                        $hsbaKpParams['benh_nhan_id'] = $hsbaKp['benh_nhan_id'];
                        $hsbaKpParams['hinh_thuc_vao_vien_id'] = 2;
                        $hsbaKpParams['vien_phi_id'] = $hsbaKp['vien_phi_id'];
                        $hsbaKpParams['bhyt_id'] = $hsbaKp['bhyt_id'];
                        //kiểm tra phòng chuyển đến có phải là phòng điều trị -> nếu đúng -> lấy trạng thái = 2 : đang điều trị ngược lại đang chờ điều trị
                        $phong = $this->phongRepository->getDataById($phongChuyenDen);//lấy ra phòng 
                        $hsbaKpParams['loai_benh_an'] = $phong->loai_benh_an;
                        $hsbaKpParams['trang_thai'] = $phong->loai_phong == 3 || $phong->loai_phong == 9 ? 2 : 0; 
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
}
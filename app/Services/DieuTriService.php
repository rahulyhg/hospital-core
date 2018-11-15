<?php
namespace App\Services;
use DB;
use App\Repositories\DieuTri\DieuTriRepository;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

class DieuTriService
{
    public function __construct(DieuTriRepository $dieuTriRepository)
    {
        $this->dieuTriRepository = $dieuTriRepository;
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
    
    
}
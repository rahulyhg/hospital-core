<?php
namespace App\Services;
use DB;

use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use App\Repositories\VienPhi\VienPhiRepository;
use App\Repositories\YLenh\YLenhRepository;
use App\Repositories\MucHuongRepository;
use App\Repositories\PhieuThu\PhieuThuRepository;
use App\Repositories\Hsba\HsbaKhoaPhongRepository;

class VienPhiService
{
    //loại phiếu thu
    const PT_THU_TIEN = 0;
    const PT_HOAN_UNG = 1;
    const PT_TAM_UNG = 2;
    
    public function __construct
    (
        VienPhiRepository $vienPhiRepository,
        YLenhRepository $yLenhRepository,
        MucHuongRepository $mucHuongRepository,
        PhieuThuRepository $phieuThuRepository,
        HsbaKhoaPhongRepository $hsbaKhoaPhongRepository
    )
    {
       $this->vienPhiRepository = $vienPhiRepository;
       $this->yLenhRepository = $yLenhRepository;
       $this->mucHuongRepository = $mucHuongRepository;
       $this->phieuThuRepository = $phieuThuRepository;
       $this->hsbaKhoaPhongRepository = $hsbaKhoaPhongRepository;
    }
    
    public function getThongTinVienPhi(array $input)
    {
        $tongTienDichVu = 0;
        $mienGiamDichVu = 0;
        $bhytThanhToan = 0;
        
        $bnTamUng = 0;
        $bvHoanUng = 0;
        $bnDaNop = 0;
        $mienGiam = 0;
        
        $dataMucHuong = $this->mucHuongRepository->getListMucHuong()->toArray();
        $dataYLenh = $this->yLenhRepository->getYLenhByVienPhiId($input['vien_phi_id']); //if (!empty($dataYLenh))
        $dctBHYT = 0;// chênh lệch % mức hưởng * giá BHYT
        $chenhLechBHYT = 0;// giá bv - giá BHYT
        foreach($dataYLenh as $item) {
            switch(intval($item['loai_vien_phi']))
            {
                case 1 ://Bình thường -> tính theo giá 
                    continue;
                case 2://BHYT = ĐCT BHYT + Chênh lệch BHYT
                    $maDoiTuong = substr ( $item['ms_bhyt'] , 0 , 2 );
                    $heSo = substr( $item['ms_bhyt'] , 2 , 1 );
                    $dataMucHuongByHeSo = array_where($dataMucHuong, function ($value, $key) use ($heSo) {
                        return $value['he_so'] == intval($heSo);
                    });
                    $arrayMaDoiTuong = explode(',', current($dataMucHuongByHeSo)['ma_doi_tuong']);
                    if (in_array($maDoiTuong, $arrayMaDoiTuong)) {
                        $dctBHYT = $dctBHYT + (1 - floatval(current($dataMucHuongByHeSo)['muc_huong_dung_tuyen'])) * floatval($item['gia_bhyt']);
                        $chenhLech = floatval($item['gia']) - floatval($item['gia_bhyt']);
                        $chenhLech = $chenhLech < 0 ? 0 : $chenhLech;
                        $chenhLechBHYT = $chenhLechBHYT + $chenhLech;
                        $bhytThanhToan = $bhytThanhToan + (floatval(current($dataMucHuongByHeSo)['muc_huong_dung_tuyen']) * floatval($item['gia_bhyt']));
                    }
                    else
                        break;
                    continue;
                case 3://Miễn phí
                    continue;
                case 4://Nước ngoài
                    continue;
            }
        }
        $tongTienDichVu = $dctBHYT + $chenhLechBHYT;
        return $tongTienDichVu;
        $dataPhieuThu = $this->phieuThuRepository->getListPhieuThuByVienPhiId($input['vien_phi_id']); //if (!empty($dataYLenh))
        foreach($dataPhieuThu as $item) {
            switch($item['loai_phieu_thu_id'])
            {
                case self::PT_THU_TIEN;
                    $bnDaNop = $bnDaNop + $item['da_tra'];
                    $mienGiam = $mienGiam + $item['mien_giam'];
                case self::PT_HOAN_UNG;
                    $bvHoanUng = $bvHoanUng + $item['da_tra'];
                case self::PT_TAM_UNG;
                    $bnTamUng = $bnTamUng + $item['da_tra'];
                
            }
        }
    }
    
    public function getMucHuong(array $input)
    {
        $dataMucHuong = $this->mucHuongRepository->getListMucHuong()->toArray();
        $msBhyt = mb_convert_case($input['ms_bhyt'], MB_CASE_UPPER, "UTF-8");
        $msBhyt = str_replace('-', '', $msBhyt);
        $maDoiTuong = substr($msBhyt, 0, 2);
        $heSo = substr($msBhyt, 2, 1);
        $dataMucHuongByHeSo = array_where($dataMucHuong, function ($value, $key) use ($heSo) {
            return $value['he_so'] == intval($heSo);
        });
        
        if($dataMucHuongByHeSo) {
            $obj = current($dataMucHuongByHeSo);
            return (float)$obj['muc_huong_dung_tuyen'];
        } else
            return 0;
    }
    
    public function getListVienPhiByHsbaId($hsbaId)
    {
        $data = $this->vienPhiRepository->getListVienPhiByHsbaId($hsbaId);
        return $data;
    } 
    
    public function createVienPhi($request)
    {
        $vienPhiKeys = [
            'hsba_id','loai_vien_phi','khoa_id','phong_id','doi_tuong_benh_nhan','bhyt_id','benh_nhan_id'
        ];        
        
        $hsbaKpKeys = [
            'hsba_khoa_phong_id','auth_users_id'
        ];
        
        $vienPhiParams = $request->only(...$vienPhiKeys);
        $hsbaKpParams = $request->only(...$hsbaKpKeys);
        $result = DB::transaction(function () use ($vienPhiParams,$hsbaKpParams) {
            try {
                $id = $this->vienPhiRepository->updateAndCreateVienPhi($vienPhiParams);
                if($id){
                    $newHsbaKp = $this->hsbaKhoaPhongRepository->updateAndCreateHsbaKp($id,$hsbaKpParams);
                    return $newHsbaKp;
                }

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
    
    // public function getInfoThanhToanVienPhi($hsbaId,$vienPhiId)
    // {
    //     $data = $this->vienPhiRepository->getInfoThanhToanVienPhi($hsbaId,$vienPhiId);
    //     return $data;
    // }    
}
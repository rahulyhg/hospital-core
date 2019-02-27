<?php
namespace App\Services;

use App\Models\SttDonTiep;
use App\Http\Resources\SttDonTiepResource;
use App\Repositories\SttDonTiep\SttDonTiepRepository;
use App\Repositories\Bhyt\BhytRepository;
use App\Repositories\Hsba\HsbaRepository;
use Validator;
use Carbon\Carbon;
use App\User;

class SttDonTiepService 
{
    public function __construct(SttDonTiepRepository $sttDonTiepRepository, BhytRepository $bhytRepository, HsbaRepository $hsbaRepository)
    {
        $this->sttDonTiepRepository = $sttDonTiepRepository;
        $this->bhytRepository = $bhytRepository;
        $this->hsbaRepository = $hsbaRepository;
    }
    
    public function getSttDonTiep(array $input)
    {
        $loaiStt = $input['loaiStt'];
        $maSoKiosk = $input['maSoKiosk'];
        $phongId = $input['phongId'];
        $benhVienId = $input['benhVienId'];
        $data = '';
        
        $stt = $this->sttDonTiepRepository->getSttDontiep($loaiStt, $maSoKiosk, $phongId, $benhVienId, $data);
        
        $sttDangPhucVu = $this->sttDonTiepRepository->getSttDangPhucVu($loaiStt, $phongId, $benhVienId);
        
        if($sttDangPhucVu != '')
            $thoiGianCho = $this->sttDonTiepRepository->calcTime($sttDangPhucVu, $loaiStt, $phongId, $benhVienId);
        else
            $thoiGianCho = '';
        
        $data = [
            'so_thu_tu' => $loaiStt . sprintf('%03d', $stt),
            'dang_phuc_vu' => $sttDangPhucVu ? $loaiStt . sprintf('%03d', $sttDangPhucVu) : '',
            'thoi_gian_cho' => $thoiGianCho
        ];
        
        return $data;
    }
    
    public function goiSttDonTiep(array $input)
    {
        $data = $this->sttDonTiepRepository->goiSttDonTiep($input);
        
        if($data !== null) 
            return new SttDonTiepResource($data);
        else 
            return $data;
    }
    
    public function loadSttDonTiep(array $input)
    {
        $stt = $this->sttDonTiepRepository->loadSttDonTiep($input);
        
        return SttDonTiepResource::collection($stt);
    }
    
    public function finishSttDonTiep($sttId)
    {
        $this->sttDonTiepRepository->finishSttDonTiep($sttId);
    }
    
    public function countSttDonTiep(array $input)
    {
        $data = $this->sttDonTiepRepository->countSttDonTiep($input);
        
        return $data;
    }
    
    public function scanCard($cardCode)
    {
        $length = strlen($cardCode); 
        
        $result = ['message' => '',
                    'data' => '',
                    'benh_nhan_cu' => 0,
                    'dang_ky_truoc' => 0];
        
        if($length > 12) {  //kiem tra co phai qrcode the bhyt khong
            $info = $this->getInfoPatientFromQRCode($cardCode);
            
            if(count($info) > 9) { 
                $bhytCode = $info['ms_bhyt'];
                
                $data = $this->bhytRepository->getInfoPatientByBhytCode($bhytCode);
                
                if($data['message'] == 'not found'){
                    $result['data'] = $info;
                } else {
                    $result['data'] = $data;
                    $result['benh_nhan_cu'] = 1;
                    $result['dang_ky_truoc'] = $data['is_dang_ky_truoc'];
                }
            } else {    //khong phai qrcode the bhyt => xuat thong bao loi
                $result ['message'] = 'error card code';
            }
        } else {    //ma the benh nhan
            $benhNhanId = (int)$cardCode;
            $data = $this->hsbaRepository->getHsbaByBenhNhanId($benhNhanId);
            
            if($data){
                $result['data'] = $data;
                $result['benh_nhan_cu'] = 1;
                $result['dang_ky_truoc'] = $data['is_dang_ky_truoc'];
            } else {    //khong phai ma the benh nhan => xuat thong bao loi
                $result ['message'] = 'error card code';
            }
        }
        
        return $result;
    }
    
    public function makeSttDonTiepWhenScanCard(array $input)
    {
        $cardCode = $input['cardCode'];
        $maSoKiosk = $input['maSoKiosk'];
        $phongId = $input['phongId'];
        $benhVienId = $input['benhVienId'];
        
        $result = ['message' => '',
                    'data' => '',
                    'benh_nhan_cu' => 0,
                    'dang_ky_truoc' => 0,
                    'stt' => '',
                    'dang_phuc_vu' => '',
                    'thoi_gian_cho' => ''];
                    
        $scanCard = $this->scanCard($cardCode);
        
        $result['message'] = $scanCard['message'];
        $result['data'] = $scanCard['data'];
        $result['benh_nhan_cu'] = $scanCard['benh_nhan_cu'];
        $result['dang_ky_truoc'] = $scanCard['dang_ky_truoc'];
        
        if($result ['message'] != 'error card code') {  //ma the hop le moi kiem tra de cap STT
            if($result['dang_ky_truoc'] == 1){  //dang ky truoc => cap STT uu tien
                $loaiStt = "A";
            } else {
                if($result['benh_nhan_cu'] == 1) {
                    if($result['data']['trang_thai_hsba'] == 1){   //hsba cu da dong => cap STT theo do tuoi luon
                        $age = Carbon::parse($result['data']['ngay_sinh'])->diffInYears(Carbon::now());
                        
                        $loaiStt = $this->getLoaiSttByAge($age);
                    } else {    //hsba cu chua dong, kiem tra co phai ngoai tru ko
                        if($result['data']['loai_benh_an'] != 20) { //khong phai ngoai tru => xuat thong bao dang co hsba ton tai
                            $result ['message'] = 'hsba exist';
                            $loaiStt = '';
                        } else {    //la ngoai tru => xuat thong bao chon tai kham hoac kham moi
                            $result ['message'] = 'option';
                            $loaiStt = '';
                        }
                    }
                } else {    //khong phai benh nhan cu nhung co the bhyt => cap STT theo do tuoi luon
                    $arr = explode('-', $result['data']['ngay_sinh']);
                    $age = Carbon::now()->year - $arr[0];
                    
                    $loaiStt = $this->getLoaiSttByAge($age);
                }
            }
            
            if($loaiStt != ''){
                $stt = $this->sttDonTiepRepository->getSttDonTiep($loaiStt, $maSoKiosk, $phongId, $benhVienId, $result['data']);
                $sttDangPhucVu = $this->sttDonTiepRepository->getSttDangPhucVu($loaiStt, $phongId, $benhVienId);
                if($sttDangPhucVu != '')
                    $thoiGianCho = $this->sttDonTiepRepository->calcTime($sttDangPhucVu, $loaiStt, $phongId, $benhVienId);
                else
                    $thoiGianCho = '';
                    
                $result['stt'] = $loaiStt . sprintf('%03d', $stt);
                $result['dang_phuc_vu'] = $sttDangPhucVu ? $loaiStt . sprintf('%03d', $sttDangPhucVu) : '';
                $result['thoi_gian_cho'] = $thoiGianCho;
            }
        }
        
        return $result;
    }
    
    public function getInfoPatientFromQRCode($qrCode)
    {
        $qrCodeParts = explode('|', $qrCode);
        
        if(count($qrCodeParts) >= 10) {
            $info['ms_bhyt'] = $qrCodeParts[0];
            $info['ho_va_ten'] = hex2bin($qrCodeParts[1]);
            $info['ten_benh_nhan'] = hex2bin($qrCodeParts[1]);
            if($qrCodeParts[2]){
                $ngaySinh = \DateTime::createFromFormat('d/m/Y', $qrCodeParts[2]);
                $info['ngay_sinh'] = $ngaySinh->format('Y-m-d');
            } else {
                $info['ngay_sinh'] = $qrCodeParts[2];
            }
            $info['gioi_tinh'] = ($qrCodeParts[3] == 1) ? 'Nam' : 'Nữ';
            $info['dia_chi'] = hex2bin($qrCodeParts[4]);
            $info['ma_benh_vien'] = $qrCodeParts[5];
            if($qrCodeParts[6]){
                $tuNgay = \DateTime::createFromFormat('d/m/Y', $qrCodeParts[6]);
                $info['tu_ngay'] = $tuNgay->format('Y-m-d');
            } else {
                $info['tu_ngay'] = $qrCodeParts[6];
            }
            // if($qrCodeParts[7]){
            //     $denNgay = \DateTime::createFromFormat('d/m/Y', $qrCodeParts[7]);
            //     $info['den_ngay'] = $denNgay->format('Y-m-d');
            // } else {
            //     $info['den_ngay'] = $qrCodeParts[7];
            // }
            if($qrCodeParts[8]){
                $ngayCap = \DateTime::createFromFormat('d/m/Y', $qrCodeParts[8]);
                $info['ngay_cap'] = $ngayCap->format('Y-m-d');
            } else {
                $info['ngay_cap'] = $qrCodeParts[8];
            }
            $info['ma_quan_ly'] = $qrCodeParts[9];
            //$info['cha_me'] = hex2bin($qrCodeParts[10]);
        } else {
            $info['ms_bhyt'] = $qrCodeParts[0];
        }
        
        return $info;
    }
    
    public function getLoaiSttByAge($age)
    {
        if($age < 7 || $age > 69) {
            $loaiStt = "A";
        } else {
            $loaiStt = "C";
        }
        
        return $loaiStt;
    }
    
}
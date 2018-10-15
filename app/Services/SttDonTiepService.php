<?php

namespace App\Services;

use App\Models\SttDonTiep;
use App\Http\Resources\SttDonTiepResource;
use App\Repositories\SttDonTiep\SttDonTiepRepository;
use App\Repositories\Bhyt\BhytRepository;
use App\Repositories\Hsba\HsbaRepository;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

class SttDonTiepService {
    public function __construct(SttDonTiepRepository $SttDonTiepRepository, BhytRepository $BhytRepository, HsbaRepository $HsbaRepository)
    {
        $this->SttDonTiepRepository = $SttDonTiepRepository;
        $this->BhytRepository = $BhytRepository;
        $this->HsbaRepository = $HsbaRepository;
    }
    
    public function getInfoPatientByStt($stt, $phong_id, $benh_vien_id){
        $data = $this->SttDonTiepRepository->getInfoPatientByStt($stt, $phong_id, $benh_vien_id);
        
        return new SttDonTiepResource($data);
    }
    
    public function getSttDonTiep(Request $request)
    {
        $loai_stt = $request->query('loai_stt', 'C');
        $ma_so_kiosk = $request->query('ma_so_kiosk', 1);
        $phong_id = $request->query('phong_id', 1);
        $benh_vien_id = $request->query('benh_vien_id', 1);
        $data = '';
        
        $stt = $this->SttDonTiepRepository->getSttDontiep($loai_stt, $ma_so_kiosk, $phong_id, $benh_vien_id, $data);
        
        $stt_dangphucvu = $this->SttDonTiepRepository->getSttDangPhucVu($loai_stt, $phong_id, $benh_vien_id);
        
        if($stt_dangphucvu != '')
            $thoigiancho = $this->SttDonTiepRepository->calcTime($stt_dangphucvu, $loai_stt, $phong_id, $benh_vien_id);
        else
            $thoigiancho = '';
        
        $data = [
            'so_thu_tu' => $loai_stt . sprintf('%03d', $stt),
            'dang_phuc_vu' => $stt_dangphucvu ? $loai_stt . sprintf('%03d', $stt_dangphucvu) : '',
            'thoi_gian_cho' => $thoigiancho
        ];
        
        return $data;
    }
    
    public function goiSttDonTiep(Request $request)
    {
        $data = $this->SttDonTiepRepository->goiSttDonTiep($request);
        
        return $data;
    }
    
    public function loadSttDonTiep(Request $request)
    {
        $stt = $this->SttDonTiepRepository->loadSttDonTiep($request);
        
        return SttDonTiepResource::collection($stt);
    }
    
    public function getInfoPatientByCard(Request $request)
    {
        $arr = $request->all();
        $card_code = $arr['card_code'];
        $ma_so_kiosk = $arr['ma_so_kiosk'];
        $phong_id = $arr['phong_id'];
        $benh_vien_id = $arr['benh_vien_id'];
        
        $length = strlen($card_code); 
        
        $result = ['message' => '',
                    'data' => '',
                    'benh_nhan_cu' => 0,
                    'dang_ky_truoc' => 0,
                    'stt' => '',
                    'dang_phuc_vu' => '',
                    'thoi_gian_cho' => ''];
        
        if($length > 12) {  //kiem tra co phai qrcode the bhyt khong
            $info = $this->getInfoPatientFromQRCode($card_code);
            //$bhytcode = $card_code;
            if(count($info) > 9) { 
                $bhytcode = $info['ma_so_bhyt'];
                
                $data = $this->BhytRepository->getInfoPatientByBhytCode($bhytcode);
                
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
            $benh_nhan_id = (int)$card_code;
            $data = $this->HsbaRepository->getHsbaByBenhNhanId($benh_nhan_id);
            
            if($data){
                $result['data'] = $data;
                $result['benh_nhan_cu'] = 1;
                $result['dang_ky_truoc'] = $data['is_dang_ky_truoc'];
            } else {    //khong phai ma the benh nhan => xuat thong bao loi
                $result ['message'] = 'error card code';
            }
        }
       
        if($result ['message'] != 'error card code') {  //ma the hop le moi kiem tra de cap STT
            if($result['dang_ky_truoc'] == 1){  //dang ky truoc => cap STT luon
                $loai_stt = "D";
            } else {
                if($result['benh_nhan_cu'] == 1) {
                    if($result['data']['trang_thai_hsba'] == 1){   //hsba cu da dong => cap STT theo do tuoi luon
                        $age = Carbon::parse($result['data']['ngay_sinh'])->diffInYears(Carbon::now());
                        
                        if($age < 7 || $age > 69) {
                            $loai_stt = "A";
                        } else {
                            $loai_stt = "C";
                        }
                    } else {    //hsba cu chua dong, kiem tra co phai ngoai tru ko
                        if($result['data']['loai_benh_an'] != 20) { //khong phai ngoai tru => xuat thong bao dang co hsba ton tai
                            $result ['message'] = 'hsba exist';
                            $loai_stt = '';
                        } else {    //la ngoai tru => xuat thong bao chon tai kham hoac kham moi
                            $result ['message'] = 'option';
                            $loai_stt = '';
                        }
                    }
                } else {    //khong phai benh nhan cu nhung co the bhyt => cap STT theo do tuoi luon
                    $arr = explode('/', $result['data']['ngay_sinh']);
                    $age = Carbon::now()->year - $arr[2];
                    
                    if($age < 7 || $age > 69) {
                        $loai_stt = "A";
                    } else {
                        $loai_stt = "C";
                    }
                }
            }
            
            if($loai_stt != ''){
                $stt = $this->SttDonTiepRepository->getSttDonTiep($loai_stt, $ma_so_kiosk, $phong_id, $benh_vien_id, $result['data']);
                $stt_dangphucvu = $this->SttDonTiepRepository->getSttDangPhucVu($loai_stt, $phong_id, $benh_vien_id);
                if($stt_dangphucvu != '')
                    $thoigiancho = $this->SttDonTiepRepository->calcTime($stt_dangphucvu, $loai_stt, $phong_id, $benh_vien_id);
                else
                    $thoigiancho = '';
                    
                $result['stt'] = $loai_stt . sprintf('%03d', $stt);
                $result['dang_phuc_vu'] = $stt_dangphucvu ? $loai_stt . sprintf('%03d', $stt_dangphucvu) : '';
                $result['thoi_gian_cho'] = $thoigiancho;
            }
        }
        
        return $result;
    }
    
    public function getInfoPatientFromQRCode($qrCode){
        $qrCodeParts = explode('|', $qrCode);
        
        if(count($qrCodeParts) >= 10) {
            $info['ma_so_bhyt'] = $qrCodeParts[0];
            $info['ten_benh_nhan'] = hex2bin($qrCodeParts[1]);
            $info['ngay_sinh'] = $qrCodeParts[2];
            $info['gioi_tinh'] = ($qrCodeParts[3] == 1) ? 'Nam' : 'Nữ';
            $info['dia_chi'] = hex2bin($qrCodeParts[4]);
            $info['ma_benh_vien'] = $qrCodeParts[5];
            $info['tu_ngay'] = $qrCodeParts[6];
            $info['den_ngay'] = $qrCodeParts[7];
            $info['ngay_cap'] = $qrCodeParts[8];
            $info['ma_quan_ly'] = $qrCodeParts[9];
            //$info['cha_me'] = hex2bin($qrCodeParts[10]);
        } else {
            $info['ma_so_bhyt'] = $qrCodeParts[0];
        }
        
        return $info;
    }

}
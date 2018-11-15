<?php
namespace App\Services;

use App\Models\SttPhongKham;
use App\Repositories\SttPhongKhamRepository;
use App\Services\HsbaKhoaPhongService;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use App\User;

class SttPhongKhamService 
{
    public function __construct(SttPhongKhamRepository $sttPhongKhamRepository, HsbaKhoaPhongService $hsbaKhoaPhongService)
    {
        $this->sttPhongKhamRepository = $sttPhongKhamRepository;
        $this->hsbaKhoaPhongService = $hsbaKhoaPhongService;
    }
    
    public function getSttPhongKham(array $params)
    {
        $phongKham = $this->sttPhongKhamRepository->countSttPhongKham($params['loai_stt'], $params['ma_nhom'], $params['khoa_id']);
        
        if($phongKham) {
            $params['phong_id'] = $phongKham->id;
            $params['ten_phong'] = $phongKham->ten_phong;
            $params['so_phong'] = $phongKham->so_phong;
            
            $stt = $this->sttPhongKhamRepository->createSttPhongKham($params);
            
            if($stt) {
                $input = ['phong_hien_tai' => $params['phong_id']];
                $this->hsbaKhoaPhongService->updateHsbaKhoaPhong($params['hsba_khoa_phong_id'], $input);
            }
            
            $data = [
                'so_thu_tu'     => $params['loai_stt'] . sprintf('%03d', $stt),
                'benh_nhan_id'  => sprintf('%012d', $params['benh_nhan_id']),
                'ten_benh_nhan' => $params['ten_benh_nhan'],
                'gioi_tinh_id'  => $params['gioi_tinh_id'],
                'phong_id'      => $params['phong_id'],
                'ten_phong'     => $params['ten_phong'],
                'so_phong'      => $params['so_phong'],
                'ms_bhyt'       => $params['ms_bhyt'],
                'yeu_cau_kham'  => $params['yeu_cau_kham']
            ];
            
            return $data;
        } else {
            return null;
        }
    }
    
    public function getListPhongKham($hsbaId)
    {
        $data = $this->sttPhongKhamRepository->getListPhongKham($hsbaId);
        
        return $data;
    }
}
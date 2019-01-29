<?php
namespace App\Services;

use App\Models\SttPhongKham;
use App\Http\Resources\SttPhongKhamResource;
use App\Repositories\SttPhongKhamRepository;
use App\Repositories\Hsba\HsbaPhongKhamRepository;
use App\Repositories\BenhVienRepository;
use App\Services\HsbaKhoaPhongService;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use App\User;
use App\Helper\AwsS3; 

class SttPhongKhamService 
{
    public function __construct(
        SttPhongKhamRepository $sttPhongKhamRepository, 
        BenhVienRepository $benhVienRepository, 
        HsbaPhongKhamRepository $hsbaPhongKhamRepository,
        HsbaKhoaPhongService $hsbaKhoaPhongService
    )
    {
        $this->sttPhongKhamRepository = $sttPhongKhamRepository;
        $this->benhVienRepository = $benhVienRepository;
        $this->hsbaKhoaPhongService = $hsbaKhoaPhongService;
        $this->hsbaPhongKhamRepository = $hsbaPhongKhamRepository;
    }
    
    public function getSttPhongKham(array $params)
    {
        $phongKham = $this->sttPhongKhamRepository->countSttPhongKham($params['loai_stt'], $params['ma_nhom'], $params['khoa_id']);
        
        if($phongKham) {
            $params['phong_id'] = $phongKham->id;
            $params['ten_phong'] = $phongKham->ten_phong;
            $params['ma_phong'] = $phongKham->ma_nhom;
            $params['so_phong'] = $phongKham->so_phong;
            
            $stt = $this->sttPhongKhamRepository->createSttPhongKham($params);
            $this->hsbaPhongKhamRepository->create($params);
            if($stt) {
                $input = ['phong_hien_tai' => $params['phong_id']];
                $this->hsbaKhoaPhongService->update($params['hsba_khoa_phong_id'], $input);
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
    
    public function goiSttPhongKham(array $input)
    {
        $data = $this->sttPhongKhamRepository->goiSttPhongKham($input);
        
        if($data !== null) 
            return new SttPhongKhamResource($data);
        else 
            return $data;
    }
    
    public function loadSttPhongKham(array $input)
    {
        $stt = $this->sttPhongKhamRepository->loadSttPhongKham($request);
        
        return SttPhongKhamResource::collection($stt);
    }
    
    public function finishSttPhongKham($sttId)
    {
        $this->sttPhongKhamRepository->finishSttPhongKham($sttId);
    }
}
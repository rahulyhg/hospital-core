<?php

namespace App\Services;

use App\Repositories\Kho\PhieuKhoRepository;
use App\Repositories\Kho\TheKhoRepository;
use App\Repositories\Kho\ChiTietPhieuKhoRepository;
use Illuminate\Http\Request;
use DB;
use Validator;

class PhieuKhoService {
    public function __construct(
        PhieuKhoRepository $phieuKhoRepository,TheKhoRepository $theKhoRepository,ChiTietPhieuKhoRepository $chiTietPhieuKhoRepository)
    {
        $this->phieuKhoRepository = $phieuKhoRepository;
        $this->theKhoRepository = $theKhoRepository;
        $this->chiTietPhieuKhoRepository = $chiTietPhieuKhoRepository;
    }
    
    public function createPhieuKho(array $input)
    {
        DB::transaction(function () use ($input) {
            $phieuKhoParams = [];
            $phieuKhoParams['kho_id']=$input['kho_id'];
            $phieuKhoParams['nhan_vien_yeu_cau']=$input['nguoi_lap_phieu_id'];
            $phieuKhoParams['thoi_gian_yeu_cau']=$input['ngay_lap_phieu'];
            $phieuKhoParams['so_chung_tu']=$input['so_chung_tu'];
            $phieuKhoParams['ncc_id']=$input['nha_cung_cap_id'];
            //$phieuKhoParams['nguoi_giao']=$input['nguoi_giao'];
            $phieuKhoParams['dia_chi_giao']=$input['dia_chi_giao'];
            $phieuKhoParams['ghi_chu']=$input['ghi_chu'];
            $phieuKhoParams['trang_thai']=0;
            $phieuKhoId = $this->phieuKhoRepository->createPhieuKho($phieuKhoParams);
            
            foreach($input['data_dich_vu'] as $item) {
                $theKhoParams = [];
                $theKhoParams['kho_id']=$input['kho_id'];
                $theKhoParams['danh_muc_thuoc_vat_tu_id']=$item['id'];
                $theKhoParams['sl_dau_ky']=$item['so_luong'];
                $theKhoParams['sl_kha_dung']=$item['so_luong'];
                $theKhoParams['sl_ton_kho_chan']=floor($item['so_luong']);
                $theKhoParams['sl_ton_kho_le']=$item['so_luong']-floor($item['so_luong']);
                $theKhoParams['gia_nhap']=$item['don_gia_nhap'];  
                //$theKhoParams['vat_nhap']=$item['vat%'];
                $theKhoParams['trang_thai']=0;   
                $theKhoId = $this->theKhoRepository->createTheKho($theKhoParams);
                
                $chiTietPhieuKhoParams = [];
                $chiTietPhieuKhoParams['phieu_kho_id']=$phieuKhoId;
                $chiTietPhieuKhoParams['danh_muc_thuoc_vat_tu_id']=$item['id'];
                $chiTietPhieuKhoParams['the_kho_id']=$theKhoId;
                $chiTietPhieuKhoParams['so_luong_nhap']=$item['so_luong'];
                //$chiTietPhieuKhoParams['vat_gia_nhap']=$item['vat%'];
                $chiTietPhieuKhoParams['gia_nhap']=$item['don_gia_nhap'];
                $chiTietPhieuKhoParams['trang_thai'] = 0; 
                $this->chiTietPhieuKhoRepository->createChiTietPhieuKho($chiTietPhieuKhoParams);
            }
        });
    }
    
    public function createPhieuYeuCau(array $input)
    {
        DB::transaction(function () use ($input) {
            $phieuKhoParams = [];
            $phieuKhoParams['kho_id']=$input['kho_id'];
            $phieuKhoParams['kho_id_xu_ly']=$input['kho_id_xu_ly'];
            $phieuKhoParams['ten_kho_xu_ly']=$input['ten_kho_xu_ly'];
            $phieuKhoParams['loai_phieu']=$input['loai_phieu'];
            $phieuKhoParams['trang_thai']=$input['trang_thai'];
            $phieuKhoParams['dien_giai']=$input['ghi_chu'];
            $phieuKhoParams['nhan_vien_yeu_cau']=$input['nguoi_lap_phieu_id'];
            $phieuKhoParams['thoi_gian_yeu_cau']=$input['ngay_lap_phieu'];
            
            $phieuKhoId = $this->phieuKhoRepository->createPhieuKho($phieuKhoParams);
            
            foreach($input['data_dich_vu'] as $item) {
                $theKhoParams = [];
                $theKhoParams['kho_id']=$input['kho_id_xu_ly'];
                $theKhoParams['danh_muc_thuoc_vat_tu_id']=$item['id'];
                $theKhoParams['so_luong']=$item['so_luong'];
 
                $theKhoId = $this->theKhoRepository->updateTheKho($theKhoParams);
                
                if($theKhoId>0) {
                    $chiTietPhieuKhoParams = [];
                    $chiTietPhieuKhoParams['phieu_kho_id']=$phieuKhoId;
                    $chiTietPhieuKhoParams['danh_muc_thuoc_vat_tu_id']=$item['id'];
                    $chiTietPhieuKhoParams['the_kho_id']=$theKhoId;
                    $chiTietPhieuKhoParams['so_luong_yeu_cau']=$item['so_luong'];
                    $chiTietPhieuKhoParams['trang_thai'] = $input['trang_thai']; 
                    $this->chiTietPhieuKhoRepository->createChiTietPhieuKho($chiTietPhieuKhoParams); 
                }
            }
        });
    }     
}
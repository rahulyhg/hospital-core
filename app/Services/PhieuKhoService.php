<?php

namespace App\Services;

use App\Repositories\Kho\PhieuKhoRepository;
use App\Repositories\Kho\TheKhoRepository;
use App\Repositories\Kho\ChiTietPhieuKhoRepository;
use App\Repositories\Kho\GioiHanRepository;
use App\Repositories\Kho\KhoRepository;
use App\Repositories\DanhMuc\DanhMucThuocVatTuRepository;
use Cviebrock\LaravelElasticsearch\Facade as Elasticsearch;
use Illuminate\Http\Request;
use DB;
use Validator;
use Carbon\Carbon;
use App\Helper\Util;

class PhieuKhoService {
    
    const THUOC_VAT_TU_DUOC_DUYET = 1;
    const THUOC_VAT_TU_KHONG_DUYET = 0;
    const SU_DUNG = 1;
    const KHONG_SU_DUNG = 0;
    
    const DA_NHAP_STATUS=32;
    const DA_DUYET_YEU_CAU_STATUS=2;
    const YEU_CAU_NHAP_STATUS=42;
    
    const LOAI_PHIEU_NHAP = 0;
    const LOAI_PHIEU_XUAT = 1;
    
    public function __construct(
        PhieuKhoRepository $phieuKhoRepository,
        TheKhoRepository $theKhoRepository,
        ChiTietPhieuKhoRepository $chiTietPhieuKhoRepository,
        KhoRepository $khoRepository,
        GioiHanRepository $gioiHanRepository,
        DanhMucThuocVatTuRepository $danhMucThuocVatTuRepository
        )
    {
        $this->phieuKhoRepository = $phieuKhoRepository;
        $this->theKhoRepository = $theKhoRepository;
        $this->chiTietPhieuKhoRepository = $chiTietPhieuKhoRepository;
        $this->khoRepository = $khoRepository;
        $this->gioiHanRepository = $gioiHanRepository;
        $this->danhMucThuocVatTuRepository = $danhMucThuocVatTuRepository;
    }
    
    public function createPhieuKho(array $input)
    {
        DB::transaction(function () use ($input) {
            $maPhieu = $this->phieuKhoRepository->getMaPhieu();
            $dataKho = $this->khoRepository->getKhoById($input['kho_id']);            
            
            $phieuKhoParams = [];
            $phieuKhoParams['kho_id']=$input['kho_id'];
            $phieuKhoParams['kho_id_xu_ly']=$input['kho_id'];
            $phieuKhoParams['ten_kho_xu_ly']=$dataKho['ten_kho'];
            $phieuKhoParams['nhan_vien_yeu_cau']=$input['nguoi_lap_phieu_id'];
            $phieuKhoParams['thoi_gian_yeu_cau']=$input['ngay_lap_phieu'];
            $phieuKhoParams['thoi_gian_duyet']=$input['ngay_lap_phieu'];
            $phieuKhoParams['nhan_vien_duyet']=$input['nguoi_lap_phieu_id'];
            $phieuKhoParams['so_chung_tu']=$input['so_chung_tu'];
            $phieuKhoParams['ncc_id']=$input['nha_cung_cap_id'];
            //$phieuKhoParams['nguoi_giao']=$input['nguoi_giao'];
            $phieuKhoParams['dia_chi_giao']=$input['dia_chi_giao'];
            $phieuKhoParams['ghi_chu']=$input['ghi_chu'];
            $phieuKhoParams['trang_thai']=self::DA_NHAP_STATUS;
            $phieuKhoParams['loai_phieu']=self::LOAI_PHIEU_NHAP;
            $phieuKhoParams['ma_phieu']=$maPhieu;
            $phieuKhoId = $this->phieuKhoRepository->createPhieuKho($phieuKhoParams);
            
            $listId = [];
            foreach($input['data_dich_vu'] as $item) {
                $listId[]=$item['id'];
                
                $theKhoParams = [];
                $theKhoParams['kho_id']=$input['kho_id'];
                $theKhoParams['danh_muc_thuoc_vat_tu_id']=$item['id'];
                $theKhoParams['sl_dau_ky']=$item['so_luong'];
                $theKhoParams['sl_kha_dung']=$item['so_luong'];
                $theKhoParams['sl_ton_kho_chan']=floor($item['so_luong']);
                //$theKhoParams['sl_ton_kho_le']=$item['so_luong']-floor($item['so_luong']);
                $theKhoParams['gia_nhap']=$item['don_gia_nhap'];  
                //$theKhoParams['vat_nhap']=$item['vat%'];
                $theKhoParams['trang_thai']=self::SU_DUNG;   
                $theKhoId = $this->theKhoRepository->createTheKho($theKhoParams);
                
                $chiTietPhieuKhoParams = [];
                $chiTietPhieuKhoParams['phieu_kho_id']=$phieuKhoId;
                $chiTietPhieuKhoParams['danh_muc_thuoc_vat_tu_id']=$item['id'];
                $chiTietPhieuKhoParams['the_kho_id']=$theKhoId;
                $chiTietPhieuKhoParams['so_luong_nhap']=$item['so_luong'];
                //$chiTietPhieuKhoParams['vat_gia_nhap']=$item['vat%'];
                $chiTietPhieuKhoParams['gia_nhap']=$item['don_gia_nhap'];
                $chiTietPhieuKhoParams['trang_thai'] = self::SU_DUNG;  
                $this->chiTietPhieuKhoRepository->createChiTietPhieuKho($chiTietPhieuKhoParams);
            }
            
            // $dmtvtEs = $this->searchThuocVatTuByListId($listId);
            // $listIdEs = [];
            // foreach($dmtvtEs as $item) {
            //     if($item['kho_id']!=$input['kho_id']) {
            //         $listIdEs[]=$item['id'];
            //     }
            // };
            
            // if(!empty($listId) && !empty($listIdEs)) {
            //     //$idDmtvtChuaTonTai = array_diff($listId,array_unique($listIdEs));
            //     $idDmtvtChuaTonTai = array_unique($listIdEs);
            //     if(!empty($idDmtvtChuaTonTai)) {
            //         echo "here";
            //         $dmtvtData = $this->danhMucThuocVatTuRepository->getByListId($idDmtvtChuaTonTai);
            //         foreach($idDmtvtChuaTonTai as $item) {
            //             $gioiHanParams = [];
            //             $gioiHanParams['kho_id'] = $input['kho_id'];
            //             $gioiHanParams['danh_muc_thuoc_vat_tu_id'] = $item['id'];
            //             $this->gioiHanRepository->createGioiHan($gioiHanParams); 
            //         }
            //         $this->pushToElasticSearch($dmtvtData);
            //     }
            // }
            
        });
    }
    
    public function searchThuocVatTuByListId(array $listId)
    {
        $params = [
            'index' => 'dmtvt_by_kho',
            'type' => 'doc',
            'body' => [
                'from' => 0,
                'size' => 1000,
                'query' => [
                    'terms' => [
                        '_id' => $listId
                    ]
                ]
            ]
        ];
        $response = Elasticsearch::search($params);   
        
        $result=[];
        foreach($response['hits']['hits'] as $item) {
            $result[] = $item['_source'];
        };
        
        return $result;         
    }
    
    public function pushToElasticSearch($dmtvtData) 
    {
        foreach($dmtvtData as $item) {
            $params = [
                            'body' => [
                                'id'                    => $item->id,
                                'nhom_danh_muc_id'      => $item->nhom_danh_muc_id,
                                'ten'                   => $item->ten,
                                'ten_khong_dau'         => Util::convertViToEn(strtolower($item->ten)),
                                'ten_bhyt'              => $item->ten_bhyt,
                                'ten_nuoc_ngoai'        => $item->ten_nuoc_ngoai,
                                'ma'                    => $item->ma,
                                'ma_bhyt'               => $item->ma_bhyt,
                                'don_vi_tinh_id'        => $item->don_vi_tinh_id,
                                'don_vi_tinh'           => $item->don_vi_tinh,
                                'stt'                   => $item->stt,
                                'nhan_vien_tao'         => $item->nhan_vien_tao,
                                'nhan_vien_cap_nhat'    => $item->nhan_vien_cap_nhat,
                                'thoi_gian_tao'         => $item->thoi_gian_tao,
                                'thoi_gian_cap_nhat'    => $item->thoi_gian_cap_nhat,
                                'hoat_chat_id'          => $item->hoat_chat_id,
                                'hoat_chat'             => $item->hoat_chat,
                                'biet_duoc_id'          => $item->biet_duoc_id,
                                'nong_do'               => $item->nong_do,
                                'duong_dung'            => $item->duong_dung,
                                'dong_goi'              => $item->dong_goi,
                                'hang_san_xuat'         => $item->hang_san_xuat,
                                'nuoc_san_xuat'         => $item->nuoc_san_xuat,
                                'trang_thai'            => $item->trang_thai,
                                'kho_id'                => $item->kho_id,
                                'loai_nhom'             => $item->loai_nhom,
                                'gia'                   => $item->gia,
                                'gia_bhyt'              => $item->gia_bhyt,
                                'gia_nuoc_ngoai'        => $item->gia_nuoc_ngoai
                            ],
                            'index' => 'dmtvt_by_kho',
                            'type' => 'doc',
                            'id' => $item->id
                        ];
            $return = Elasticsearch::index($params); 
        }
    }
    
    public function createPhieuYeuCau(array $input)
    {
        DB::transaction(function () use ($input) {
            $maPhieu = $this->phieuKhoRepository->getMaPhieu();
            
            $phieuKhoParams = [];
            $phieuKhoParams['kho_id']=$input['kho_id'];
            $phieuKhoParams['kho_id_xu_ly']=$input['kho_id_xu_ly'];
            $phieuKhoParams['ten_kho_xu_ly']=$input['ten_kho_xu_ly'];
            $phieuKhoParams['loai_phieu']=$input['loai_phieu'];
            $phieuKhoParams['trang_thai']=self::DA_DUYET_YEU_CAU_STATUS;
            $phieuKhoParams['dien_giai']=$input['ghi_chu'];
            $phieuKhoParams['nhan_vien_yeu_cau']=$input['nguoi_lap_phieu_id'];
            $phieuKhoParams['thoi_gian_yeu_cau']=$input['ngay_lap_phieu'];
            $phieuKhoParams['ma_phieu']=$maPhieu;
            
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
                    $chiTietPhieuKhoParams['trang_thai'] = self::THUOC_VAT_TU_KHONG_DUYET; 
                    $this->chiTietPhieuKhoRepository->createChiTietPhieuKho($chiTietPhieuKhoParams); 
                }
            }
        });
    }
    
    public function getListPhieuKhoByKhoIdXuLy($startDay,$endDay,$khoIdXuLy)
    {
        $data = $this->phieuKhoRepository->getListPhieuKhoByKhoIdXuLy($startDay,$endDay,$khoIdXuLy);
        return $data;
    }
    
    public function createPhieuXuat($phieuKhoId,$nhanVienDuyetId)
    {
        DB::transaction(function () use ($phieuKhoId,$nhanVienDuyetId) {
            $data = $this->phieuKhoRepository->updateAndGetPhieuKho($phieuKhoId,$nhanVienDuyetId);
            $dataKho = $this->khoRepository->getKhoById($data['kho_id_xu_ly']);
            $maPhieu = $this->phieuKhoRepository->getMaPhieu();            
            
            $phieuKhoParams = [];
            $phieuKhoParams['kho_id']=$data['kho_id_xu_ly'];
            $phieuKhoParams['kho_id_xu_ly']=$data['kho_id'];
            $phieuKhoParams['ten_kho_xu_ly']=$dataKho['ten_kho'];
            $phieuKhoParams['loai_phieu']=self::LOAI_PHIEU_XUAT;
            $phieuKhoParams['trang_thai']=self::YEU_CAU_NHAP_STATUS;
            $phieuKhoParams['nhan_vien_yeu_cau']=$nhanVienDuyetId;
            $phieuKhoParams['thoi_gian_yeu_cau']=Carbon::now();
            $phieuKhoParams['phieu_kho_yeu_cau_id']=$phieuKhoId;
            $phieuKhoParams['ma_phieu']=$maPhieu;
            
            $this->phieuKhoRepository->createPhieuKho($phieuKhoParams);
            
            $chiTietPhieuKhoParams['trang_thai']=self::THUOC_VAT_TU_DUOC_DUYET;
            
            $chiTietPhieuKhoData = $this->chiTietPhieuKhoRepository->updateAndGetChiTietPhieuKho($phieuKhoId,$chiTietPhieuKhoParams);
            
            $arrDmtvt=[];
            foreach($chiTietPhieuKhoData as $item) {
                $arrDmtvt[]=$item['danh_muc_thuoc_vat_tu_id'];
            
            }
            $dataTheKho = $this->theKhoRepository->getTheKho($data['kho_id_xu_ly'],$arrDmtvt);
            $arrIdTheKho = [];
            $arrTheKho = [];
            foreach($chiTietPhieuKhoData as $item) {
                $id = $item['danh_muc_thuoc_vat_tu_id'];
                $soLuongYeuCau = $item['so_luong_yeu_cau'];
                
                foreach($dataTheKho as $itemDataTheKho) {
                    if($itemDataTheKho['danh_muc_thuoc_vat_tu_id']==$id && $soLuongYeuCau > 0 && $itemDataTheKho['sl_ton_kho_chan'] > 0){
                        $chenhLech = $itemDataTheKho['sl_ton_kho_chan'] - $soLuongYeuCau;
                        if($chenhLech < 0) {
                            $soLuongTon = 0;
                            $soLuongYeuCau = $chenhLech;
                        } else {
                            $soLuongTon = $chenhLech;
                            $soLuongYeuCau = 0;
                        }
                        
                        //$arrIdTheKho[] = $itemDataTheKho['id'];
                        
                        $arrTheKho[] = [
                            'id'                    => $itemDataTheKho['id'],
                            'sl_ton_kho_chan'     => $soLuongTon
                        ];
                    }
                }
            }

            foreach($arrTheKho as $item) {
                $this->theKhoRepository->updateSoLuongTon($item);
            }
        });
    }
    
    public function createPhieuNhap($phieuKhoId,$nhanVienDuyetId)
    {
        DB::transaction(function () use ($phieuKhoId,$nhanVienDuyetId) {
            $data = $this->phieuKhoRepository->getPhieuKhoById($phieuKhoId);
            $dataKho = $this->khoRepository->getKhoById($data['kho_id_xu_ly']);
            $maPhieu = $this->phieuKhoRepository->getMaPhieu();            
            
            $phieuKhoParams = [];
            $phieuKhoParams['kho_id']=$data['kho_id_xu_ly'];
            $phieuKhoParams['kho_id_xu_ly']=$data['kho_id_xu_ly'];
            $phieuKhoParams['ten_kho_xu_ly']=$dataKho['ten_kho'];
            $phieuKhoParams['loai_phieu']=self::LOAI_PHIEU_NHAP;
            $phieuKhoParams['trang_thai']=self::DA_NHAP_STATUS;
            $phieuKhoParams['nhan_vien_duyet']=$nhanVienDuyetId;
            $phieuKhoParams['thoi_gian_yeu_cau']=Carbon::now();
            $phieuKhoParams['thoi_gian_duyet']=Carbon::now();
            $phieuKhoParams['phieu_kho_yeu_cau_id']=$data['phieu_kho_yeu_cau_id'];
            $phieuKhoParams['ma_phieu']=$maPhieu;
            
            $this->phieuKhoRepository->updateTrangThaiPhieuKho($phieuKhoId,self::DA_NHAP_STATUS);
            $this->phieuKhoRepository->createPhieuKho($phieuKhoParams);
            
            $chiTietPhieuKhoData = $this->chiTietPhieuKhoRepository->getByPhieuKhoId($data['phieu_kho_yeu_cau_id']);
            
            //$gioiHanParams = [];
            foreach($chiTietPhieuKhoData as $item) {
                if($item['trang_thai']==self::THUOC_VAT_TU_DUOC_DUYET) {
                    $theKhoParams = [];
                    $theKhoParams['kho_id'] = $data['kho_id_xu_ly'];
                    $theKhoParams['danh_muc_thuoc_vat_tu_id'] = $item['danh_muc_thuoc_vat_tu_id'];
                    $theKhoParams['sl_dau_ky'] = $item['so_luong_yeu_cau'];
                    $theKhoParams['sl_kha_dung'] = $item['so_luong_yeu_cau'];
                    $theKhoParams['sl_ton_kho_chan'] = floor($item['so_luong_yeu_cau']);
                    //$theKhoParams['sl_ton_kho_le'] = $item['so_luong_yeu_cau']-floor($item['so_luong_yeu_cau']);
                    
                    $this->theKhoRepository->createTheKho($theKhoParams);
                    
                    // $gioiHanParams[] = [
                    //     'kho_id' => $data['kho_id_xu_ly'],
                    //     'danh_muc_thuoc_vat_tu_id' => $item['danh_muc_thuoc_vat_tu_id']
                    //     ];
                }
            }

        });
    }
    
    public function getChiTietPhieuXuatNhap($phieuKhoId)
    {
        $phieuKhoData = $this->phieuKhoRepository->getThongTinPhieuKhoById($phieuKhoId);
        $chiTietPhieuKhoData = $this->chiTietPhieuKhoRepository->getThongTinChiTietByPhieuKhoId($phieuKhoId);
        
        $data=[
            'phieu_kho_data'            =>$phieuKhoData,
            'chi_tiet_phieu_kho_data'   =>$chiTietPhieuKhoData
            ];
        return $data;
    }
}
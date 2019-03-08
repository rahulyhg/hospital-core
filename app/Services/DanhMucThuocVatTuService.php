<?php

namespace App\Services;

use App\Repositories\DanhMuc\DanhMucThuocVatTuRepository;
use App\Repositories\HoatChatRepository;

use Illuminate\Http\Request;
use App\Helper\Util;
use Cviebrock\LaravelElasticsearch\Facade as Elasticsearch;

class DanhMucThuocVatTuService
{
    public function __construct(DanhMucThuocVatTuRepository $repository,HoatChatRepository $hoatChatRepository)
    {
        $this->repository = $repository;
        $this->hoatChatRepository = $hoatChatRepository;
    }
    
    // public function getListDanhMucThuocVatTu($limit, $page)
    // {
    //     $data = $this->repository->getListDanhMucThuocVatTu($limit, $page);
        
    //     return $data;
    // }
    
    // public function getDmdvById($dmdvId)
    // {
    //     $data = $this->repository->getDataDanhMucThuocVatTuById($dmdvId);
        
    //     return $data;
    // }

    // public function createDanhMucThuocVatTu(array $input)
    // {
    //     $id = $this->repository->createDanhMucThuocVatTu($input);
        
    //     return $id;
    // }
    
    // public function updateDanhMucThuocVatTu($dmdvId, array $input)
    // {
    //     $this->repository->updateDanhMucThuocVatTu($dmdvId, $input);
    // }
    
    // public function deleteDanhMucThuocVatTu($dmdvId)
    // {
    //     $this->repository->deleteDanhMucThuocVatTu($dmdvId);
    // }
    
    public function getThuocVatTuByLoaiNhom($loaiNhom)
    {
        $data = $this->repository->getThuocVatTuByLoaiNhom($loaiNhom);
        
        return $data;
    }
    
    public function getThuocVatTuByCode($maNhom, $loaiNhom)
    {
        $data = $this->repository->getThuocVatTuByCode($maNhom, $loaiNhom);
        
        return $data;
    }
    
    // public function pushToRedis()
    // {
    //     $data = $this->repository->getAllThuocVatTu();
    //     foreach($data as $item){
    //         $arrayItem=[
    //             'id'                    => (string)$item->id ?? '-',
    //             'nhom_danh_muc_id'      => (string)$item->nhom_danh_muc_id ?? '-',
    //             'ten'                   => (string)$item->ten ?? '-', 
    //             'ten_bhyt'              => $item->ten_bhyt ?? '-',
    //             'ten_nuoc_ngoai'        => (string)$item->ten_nuoc_ngoai ?? '-',
    //             'ky_hieu'               => (string)$item->ky_hieu ?? '-',
    //             'ma_bhyt'               => (string)$item->ma_bhyt ?? '-',
    //             'don_vi_tinh_id'        => (string)$item->don_vi_tinh_id ?? '-',
    //             'stt'                   => $item->stt ?? '-',
    //             'nhan_vien_tao'         => (string)$item->nhan_vien_tao ?? '-',
    //             'nhan_vien_cap_nhat'    => (string)$item->nhan_vien_cap_nhat ?? '-',
    //             'thoi_gian_tao'         => (string)$item->thoi_gian_tao ?? '-',
    //             'thoi_gian_cap_nhat'    => (string)$item->thoi_gian_cap_nhat ?? '-',
    //             'hoat_chat_id'          => (string)$item->hoat_chat_id ?? '-',
    //             'biet_duoc_id'          => (string)$item->biet_duoc_id ?? '-',
    //             'nong_do'               => (string)$item->nong_do ?? '-',
    //             'duong_dung'            => (string)$item->duong_dung ?? '-',
    //             'dong_goi'              => (string)$item->dong_goi ?? '-',
    //             'hang_san_xuat'         => (string)$item->hang_san_xuat ?? '-',
    //             'nuoc_san_xuat'         => (string)$item->nuoc_san_xuat ?? '-',
    //             'trang_thai'            => (string)$item->trang_thai ?? '-'
    //             ];            
    //         $this->danhMucThuocVatTuRedisRepository->_init();
    //         //$suffix = $item['nhom_danh_muc_id'].':'.$item['id'].":".Util::convertViToEn(str_replace(" ","_",strtolower($item['ten'])));
    //         $suffix='test';
    //         $this->danhMucThuocVatTuRedisRepository->hmset($suffix,$arrayItem);            
    //     };
    // }
    
    // public function getListByKeywords($keyWords)
    // {
    //     $this->danhMucThuocVatTuRedisRepository->_init();
    //     $data = $this->danhMucThuocVatTuRedisRepository->getListByKeywords($keyWords);
    //     return $data;
    // }
    
    public function getAllThuocVatTu()
    {
        $data = $this->repository->getAllThuocVatTu();
        return $data;
    }
    
    public function pushToElasticSearch()
    {
        $lastParams = [
            'index' => 'dmtvt',
            'type' => 'doc',
            'size' => 1,
            'body' => [
                'sort' =>[
                    'id' => [
                        'order' => 'desc'
                        ]
                ]
            ]
        ];
        $last = Elasticsearch::search($lastParams);
        $lastId = $last['hits']['hits'][0]['_source']['id'];
        
        $data = $this->repository->getThuocVatTu($lastId);
        foreach($data as $item) {
            
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
                            'index' => 'dmtvt',
                            'type' => 'doc',
                            'id' => $item->id,
                        ];
            $return = Elasticsearch::index($params);  
        };
    }
    
    public function searchThuocVatTuByKeywords($keyWords)
    {
        $params = [
            'index' => 'dmtvt',
            'type' => 'doc',
            'body' => [
                'from' => 0,
                'size' => 1000,
                'query' => [
                    'wildcard' => [
                        'ten' => '*'.$keyWords.'*'
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
    
    public function searchThuocVatTuByListId(array $listId)
    {
        $params = [
            'index' => 'dmtvt',
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
    
}
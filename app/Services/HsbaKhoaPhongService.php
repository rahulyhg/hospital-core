<?php
namespace App\Services;

// Framework Libraries
use Illuminate\Http\Request;
use Validator;

// Models
use App\Models\HsbaKhoaPhong;

// Repositories
use App\Repositories\Hsba\HsbaKhoaPhongRepository;
use App\Repositories\Sqs\Hsba\HsbaKhoaPhongRepository as HsbaKhoaPhongSqsRepository;
use App\Repositories\Redis\Hsba\HsbaKhoaPhongRepository as HsbaKhoaPhongRedisRepository;
use App\Repositories\BenhVienRepository;

// Resources (output formatter)
use App\Http\Resources\HsbaKhoaPhongResource;

class HsbaKhoaPhongService 
{
    private $dataQueue = [
        'message_attributes' => [],
        'message_body' => []
    ];
    
    public function __construct(
        HsbaKhoaPhongRepository $hsbaKhoaPhongRepository,
        BenhVienRepository $benhVienRepository, 
        HsbaKhoaPhongSqsRepository $hsbaKhoaPhongSqsRepository,
        HsbaKhoaPhongRedisRepository $hsbaKhoaPhongRedisRepository
    )
    {
        $this->hsbaKhoaPhongRepository = $hsbaKhoaPhongRepository;
        $this->benhVienRepository = $benhVienRepository;
        $this->hsbaKhoaPhongSqsRepository = $hsbaKhoaPhongSqsRepository;
        $this->hsbaKhoaPhongRedisRepository = $hsbaKhoaPhongRedisRepository;
    }
    
    public function getList($phongId, $benhVienId, $dataBenhVienThietLap, $startDay, $endDay, $limit, $page, $keyword, $status)
    {
        $data = $this->hsbaKhoaPhongRepository->getList($phongId, $benhVienId, $dataBenhVienThietLap, $startDay, $endDay, $limit, $page, $keyword, $status);
        
        return $data;
    }
    
    public function update($hsbaKhoaPhongId, array $params)
    {
        $this->hsbaKhoaPhongRepository->update($hsbaKhoaPhongId, $params);
    }
    
    public function getByHsbaId($hsbaId)
    {
        $data = $this->hsbaKhoaPhongRepository->getByHsbaId($hsbaId);
         
        return $data;
    }
    
    public function getById($hsbaKhoaPhongId)
    {
        $data = $this->hsbaKhoaPhongRepository->getById($hsbaKhoaPhongId);
        
        return $data;
    }
    
    public function getLichSuKhamDieuTri($id)
    {
        $data = $this->hsbaKhoaPhongRepository->getLichSuKhamDieuTri($id);
        return $data;
    }    
    
    public function getBenhVienThietLap($id) {
        $data = $this->benhVienRepository->getBenhVienThietLap($id);
        return $data;
    }
    
    public function setQueueAttribute($benhVienId,$khoaId,$phongId,$ngayVaoVien) {
        $messageAttributes = [
            'benh_vien_id' => ['DataType' => "Number",
                                'StringValue' => $benhVienId
                            ],
            'khoa_id' => ['DataType' => "Number",
                                'StringValue' => $khoaId
                            ],
            'phong_id' => ['DataType' => "Number",
                                'StringValue' => $phongId
                            ],
            'ngay_vao_vien' => ['DataType' => "String",
                                'StringValue' => $ngayVaoVien
                            ]                
        ];
        $this->dataQueue['message_attributes'] = $messageAttributes;

    }
    
    public function setQueueBody(array $messageBody) {
        $this->dataQueue['message_body'] = $messageBody;
    }
    
    public function pushToQueue() {
        $this->hsbaKhoaPhongSqsRepository->push(
            $this->dataQueue['message_attributes'],$this->dataQueue['message_body']
        );
    }
    
    public function addToCache($messageId,$messageAttributes,$messageBody){
        
        $benhVienId = $messageAttributes['benh_vien_id']['stringValue'];
        $khoaId = $messageAttributes['khoa_id']['stringValue'];
        $phongId= $messageAttributes['phong_id']['stringValue'];
        $ngayVaoVien = $messageAttributes['ngay_vao_vien']['stringValue'];
        $messageBody = $messageBody;
        $hsbaKPId = $messageBody['hsba_khoa_phong_id'];
        $suffix = $benhVienId.':'.$khoaId.':'.$phongId.':'.$ngayVaoVien.":".$hsbaKPId;
        $this->hsbaKhoaPhongRedisRepository->_init();
        $this->hsbaKhoaPhongRedisRepository->hmset($suffix,$messageBody);
        
        return true;
    }
    
    public function batDauKham($hsbaKhoaPhongId)
    {
        $this->hsbaKhoaPhongRepository->batDauKham($hsbaKhoaPhongId);
    }
}
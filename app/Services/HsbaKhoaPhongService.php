<?php
namespace App\Services;

// Framework Libraries
use Illuminate\Http\Request;
use Validator;
use Storage;
use Exception;
use App\Helper\AwsS3;

// Models
use App\Models\HsbaKhoaPhong;

// Repositories
use App\Repositories\Hsba\HsbaKhoaPhongRepository;
use App\Repositories\Sqs\Hsba\HsbaKhoaPhongRepository as HsbaKhoaPhongSqsRepository;
use App\Repositories\Redis\Hsba\HsbaKhoaPhongRepository as HsbaKhoaPhongRedisRepository;
use App\Repositories\BenhVienRepository;
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
    
    /**
     * Deprecating
     */
    public function getList($phongId, $benhVienId, $dataBenhVienThietLap, $startDay, $endDay, $limit, $page, $keyword, $status, $option)
    {
        $data = $this->hsbaKhoaPhongRepository->getList($phongId, $benhVienId, $dataBenhVienThietLap, $startDay, $endDay, $limit, $page, $keyword, $status, $option);
        
        return $data;
    }
    
    public function getListKhoaKhamBenh($benhVienId, $phongId, $limit, $page, $options) {
        $dataBenhVienThietLap = $this->getBenhVienThietLap($benhVienId);
        $khoaId = $dataBenhVienThietLap['khoaKhamBenh'];
        $phongDonTiepId = $dataBenhVienThietLap['phongDonTiepID'];
        if($phongDonTiepId == $phongId)
            $phongId = null;
        $options['loai_benh_an'] = $options['loai_benh_an'] ? $options['loai_benh_an'] : HsbaKhoaPhongRepository::BENH_AN_KHAM_BENH;
        return $this->getListV2($benhVienId, $khoaId, $phongId, $limit, $page, $options);
    }
    
    public function getListKhoaNoiTru() {
        // TBD
        /*
        $dataBenhVienThietLap = $this->getBenhVienThietLap($benhVienId);
        $khoaId = $dataBenhVienThietLap['khoaNoiTru'];
        return $this->getListV2($benhVienId, $khoaId, $phongId, $limit, $page, $options);
        */
    }
    
    public function getListKhoaCapCuu() {
        // TBD
        /*
        $dataBenhVienThietLap = $this->getBenhVienThietLap($benhVienId);
        $khoaId = $dataBenhVienThietLap['khoaCapCuu'];
        return $this->getListV2($benhVienId, $khoaId, $phongId, $limit, $page, $options);
        */
    }
    
    private function getListV2($benhVienId, $khoaId, $phongId, $limit, $page, $options) {
        $repo = $this->hsbaKhoaPhongRepository;
        $dataBenhVienThietLap = $this->getBenhVienThietLap($benhVienId);
        $khoaId = $dataBenhVienThietLap['khoaHienTai'];
        $phongId = $phongId;
        
        $repo = $repo   ->setKhoaPhongParams($benhVienId, $khoaId, $phongId)
                        ->setKeyWordParams($options['keyword']??null)
                        ->setKhoangThoiGianVaoVienParams($options['thoi_gian_vao_vien_from']??null, $options['thoi_gian_vao_vien_to']??null)
                        ->setKhoangThoiGianRaVienParams($options['thoi_gian_ra_vien_from']??null, $options['thoi_gian_ra_vien_to']??null)
                        ->setLoaiVienPhiParams($options['loai_vien_phi']??null)
                        ->setLoaiBenhAnParams($options['loai_benh_an']??null)
                        ->setStatusHsbaKpParams($options['status_hsba_khoa_phong']??-1)
                        ->setStatusHsbaParams($options['status_hsba']??-1)
                        ->setPaginationParams($limit, $page);
        $data = $repo->getListV2();                
        return $data;
    }
    
    public function getListThuNgan($benhVienId, $limit, $page, $options) {
        $repo = $this->hsbaKhoaPhongRepository;
        
        $repo = $repo   ->setBenhVienParams($benhVienId)
                        ->setKeyWordParams($options['keyword']??null)
                        ->setKhoangThoiGianVaoVienParams($options['thoi_gian_vao_vien_from']??null, $options['thoi_gian_vao_vien_to']??null)
                        ->setKhoangThoiGianRaVienParams($options['thoi_gian_ra_vien_from']??null, $options['thoi_gian_ra_vien_to']??null)
                        ->setLoaiVienPhiParams($options['loai_vien_phi']??null)
                        ->setLoaiBenhAnParams($options['loai_benh_an']??null)
                        ->setStatusHsbaParams($options['status_hsba']??-1)
                        ->setPaginationParams($limit, $page);
        $data = $repo->getListThuNgan();                
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
        return $this;
    }
    
    public function setQueueBody(array $messageBody) {
        $this->dataQueue['message_body'] = $messageBody;
        return $this;
    }
    
    public function pushToQueue() {
        $this->hsbaKhoaPhongSqsRepository->push(
            $this->dataQueue['message_attributes'],$this->dataQueue['message_body']
        );
        return $this;
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
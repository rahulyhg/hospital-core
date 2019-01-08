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
    
    public function getList($phongId, $benhVienId, $dataBenhVienThietLap, $startDay, $endDay, $limit, $page, $keyword, $status, $option)
    {
        $data = $this->hsbaKhoaPhongRepository->getList($phongId, $benhVienId, $dataBenhVienThietLap, $startDay, $endDay, $limit, $page, $keyword, $status, $option);
        
        return $data;
    }
    
    public function update($hsbaKhoaPhongId, array $params)
    {
        $fileUpload = [];
        // Config S3
        $s3 = new AwsS3();
        
        // GET OLD FILE
        $item = $this->hsbaKhoaPhongRepository->getById($hsbaKhoaPhongId);
        $fileItem =  json_decode($item->upload_file_hoi_benh, true);
        
        
        // Remove File old
        if(!empty($params['oldFiles'])) {
            foreach($fileItem as $file) {
                if(!in_array($file, $params['oldFiles'])) {
                    $s3->deleteObject($file);
                }
                else {
                    $fileUpload[] = $file;
                }
            }
            unset($params['oldFiles']);
        }
        else {
            if(!empty($fileItem)) {
                foreach($fileItem as $file) {
                    $s3->deleteObject($file);
                }
            }
        }
        
        if(!empty($params['files'])) {
            $arrayExtension = ['jpg', 'jpeg', 'png', 'bmp', 'mp3', 'mp4', 'pdf', 'docx'];
            foreach($params['files'] as $file) {
                if(!in_array($file->getClientOriginalExtension(), $arrayExtension)) {
                    throw new Exception('File chứa định dạng ko cho phép để upload');
                }
            }
            
            foreach ($params['files'] as $file) {
                $imageFileName = time() . '_' . rand(0, 999999) . '.' . $file->getClientOriginalExtension();
                //$move = $file->move('upload/hsbakp/hoibenh', $imageFileName);
                $fileUpload[] = $imageFileName;
                
                $result = $s3->putObject($imageFileName, $file);
            }
                
            if(!empty($fileUpload)) {
                $params['upload_file_hoi_benh'] = json_encode($fileUpload);
            }
            else {
                $params['upload_file_hoi_benh'] = NULL;
            }
            
            unset($params['files']);
        }
        $this->hsbaKhoaPhongRepository->update($hsbaKhoaPhongId, $params);
        $data = array(
            'status'  => 'success'
        );
        return $data;
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
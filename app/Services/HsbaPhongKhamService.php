<?php
namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\Hsba\HsbaPhongKhamRepository;
use App\Repositories\BenhVienRepository;
use App\Helper\AwsS3; 

class HsbaPhongKhamService {
    public function __construct(
        BenhVienRepository $benhVienRepository, 
        HsbaPhongKhamRepository $hsbaPhongKhamRepository
    )
    {
        $this->benhVienRepository = $benhVienRepository;
        $this->hsbaPhongKhamRepository = $hsbaPhongKhamRepository;
    }
    
    public function updatePhongKham($hsbaKhoaPhongId, array $params)
    {
        // Get Data Benh Vien Thiet Lap
        if(empty($params['benh_vien_id'])) $params['benh_vien_id'] = 1;
        $dataBenhVienThietLap = $this->getBenhVienThietLap($params['benh_vien_id']);
        unset($params['benh_vien_id']);
        
        $fileUpload = [];
        // Config S3
        $s3 = new AwsS3($dataBenhVienThietLap['bucket']);
        
        // GET OLD FILE
        $item = $this->hsbaPhongKhamRepository->getDetailHSBAPhongKham($params['hsba_id'], $params['phong_id']);
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
                $pathName = $file->getPathName();
                $mimeType = $file->getMimeType();
                $result = $s3->putObject($imageFileName, $pathName, $mimeType);
            }
                
            if(!empty($fileUpload)) {
                $params['upload_file_hoi_benh'] = json_encode($fileUpload);
            }
            else {
                $params['upload_file_hoi_benh'] = NULL;
            }
            
            unset($params['files']);
        }
        $this->hsbaPhongKhamRepository->updatePhongKham($hsbaKhoaPhongId, $params);
        $data = [
            'status'    => 'success'
        ];
        return $data;
    }
    
    public function getBenhVienThietLap($id) {
        $data = $this->benhVienRepository->getBenhVienThietLap($id);
        return $data;
    }
    
    public function getDetailHSBAPhongKham($hsbaId, $phongId) {
        $data = $this->hsbaPhongKhamRepository->getDetailHSBAPhongKham($hsbaId, $phongId);
        return $data;
    }
    
}
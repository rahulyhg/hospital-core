<?php
namespace App\Services;

use App\Models\HsbaKhoaPhong;
use App\Http\Resources\HsbaKhoaPhongResource;
use App\Repositories\Hsba\HsbaKhoaPhongRepository;
use App\Repositories\BenhVienRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Validator;
use Storage;
use Exception;

class HsbaKhoaPhongService 
{
    public function __construct(HsbaKhoaPhongRepository $hsbaKhoaPhongRepository, BenhVienRepository $benhVienRepository)
    {
        $this->hsbaKhoaPhongRepository = $hsbaKhoaPhongRepository;
        $this->benhVienRepository = $benhVienRepository;
    }
    
    public function getList($phongId, $benhVienId, $dataBenhVienThietLap, $startDay, $endDay, $limit, $page, $keyword, $status)
    {
        $data = $this->hsbaKhoaPhongRepository->getList($phongId, $benhVienId, $dataBenhVienThietLap, $startDay, $endDay, $limit, $page, $keyword, $status);
        
        return $data;
    }
    
    public function update($hsbaKhoaPhongId, array $params)
    {
        $fileUpload = [];
        // Config S3
        $s3 = new \Aws\S3\S3Client([
        	'region'  => config('filesystems.disks.s3.region'),
        	'version' => 'latest',
        	'credentials' => [
        	    'key'    => config('filesystems.disks.s3.key'),
        	    'secret' => config('filesystems.disks.s3.secret'),
        	]
        ]);
        
        // GET OLD FILE
        $item = $this->hsbaKhoaPhongRepository->getById($hsbaKhoaPhongId);
        $fileItem =  json_decode($item->upload_file_hoi_benh, true);
        
        
        // Remove File old
        if(!empty($params['oldFiles'])) {
            foreach($fileItem as $file) {
                if(!in_array($file, $params['oldFiles'])) {
                    $s3->deleteObject([
                        'Bucket' => config('filesystems.disks.s3.bucket'),
                        'Key'    => $file
                    ]);
                }
                else {
                    $fileUpload[] = $file;
                }
            }
            unset($params['oldFiles']);
        }
        else {
            foreach($fileItem as $file) {
                $s3->deleteObject([
                    'Bucket' => config('filesystems.disks.s3.bucket'),
                    'Key'    => $file
                ]);
            }
        }
        
        if(!empty($params['files'])) {
            $arrayExtension = ['jpg', 'jpeg', 'png', 'bmp', 'mp3', 'mp4', 'pdf', 'docx'];
            foreach($params['files'] as $file) {
                if(!in_array($file->getClientOriginalExtension(), $arrayExtension)) {
                    throw new Exception('File chứa định dạng ko cho phép để upload');
                }
            }
            
            if (!is_dir('upload/hsbakp/hoibenh')) {
                mkdir('upload/hsbakp/hoibenh', 0777, true);
            }
        
            if(!empty($params['files'])) {
                foreach ($params['files'] as $file) {
                    $imageFileName = time() . '_' . rand(0, 999999) . '.' . $file->getClientOriginalExtension();
                    //$move = $file->move('upload/hsbakp/hoibenh', $imageFileName);
                    $fileUpload[] = $imageFileName;
                    
                    $result = $s3->putObject([
                    	'Bucket' => config('filesystems.disks.s3.bucket'),
                    	'Key'    => $imageFileName,
                    	'SourceFile' => $file->getPathName(),
                    	'ContentType' => $file->getMimeType()
                    ]);
                }
                
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
    
    public function batDauKham($hsbaKhoaPhongId)
    {
        $this->hsbaKhoaPhongRepository->batDauKham($hsbaKhoaPhongId);
    }
}
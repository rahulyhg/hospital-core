<?php
namespace App\Services;
use App\Repositories\Kho\KhoRepository;
use App\Repositories\BenhVienRepository;
use Illuminate\Http\Request;
use Validator;
class KhoService {
    public function __construct(
        KhoRepository $khoRepository,BenhVienRepository $benhVienRepository)
    {
        $this->khoRepository = $khoRepository;
        $this->benhVienRepository = $benhVienRepository;
    }
    public function getListKho($limit, $page, $keyWords, $benhVienId)
    {
        $data = $this->khoRepository->getListKho($limit, $page, $keyWords, $benhVienId);
        if(!empty($data['data'])){
            foreach($data['data'] as $item){
                $benhVien = $this->benhVienRepository->getById($item->benh_vien_id);
                $item['ten_benh_vien']=$benhVien['ten'];
            }
        }
        return $data;
    }
    
    public function createKho(array $input)
    {
        $id = $this->khoRepository->createKho($input);
        return $id;
    } 
    
    public function updateKho($id, array $input)
    {
        $this->khoRepository->updateKho($id, $input);
    }
    
    public function deleteKho($id)
    {
        $this->khoRepository->deleteKho($id);
    }
    
    public function getKhoById($id)
    {
        $data = $this->khoRepository->getKhoById($id);
        return $data;
    }
    
    public function getAllKhoByBenhVienId($benhVienId)
    {
        $data = $this->khoRepository->getAllKhoByBenhVienId($benhVienId);
        return $data;
    }    
}
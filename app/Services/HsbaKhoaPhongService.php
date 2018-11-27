<?php
namespace App\Services;

use App\Models\HsbaKhoaPhong;
use App\Http\Resources\HsbaKhoaPhongResource;
use App\Repositories\Hsba\HsbaKhoaPhongRepository;
use Illuminate\Http\Request;
use Validator;

class HsbaKhoaPhongService 
{
    public function __construct(HsbaKhoaPhongRepository $hsbaKhoaPhongRepository)
    {
        $this->hsbaKhoaPhongRepository = $hsbaKhoaPhongRepository;
    }
    
    public function getListBenhNhan($phongId, $benhVienId, $startDay, $endDay, $limit, $page, $keyword, $status)
    {
        $data = $this->hsbaKhoaPhongRepository->getListBenhNhan($phongId, $benhVienId, $startDay, $endDay, $limit, $page, $keyword, $status);
        
        return $data;
    }
    
    public function updateHsbaKhoaPhong($hsbaKhoaPhongId, array $params)
    {
        $this->hsbaKhoaPhongRepository->updateHsbaKhoaPhong($hsbaKhoaPhongId, $params);
    }
    
    public function getByHsbaId($hsbaId)
    {
        $data = $this->hsbaKhoaPhongRepository->getByHsbaId($hsbaId);
         
        return $data;
    }
    
    public function getHsbaKhoaPhongById($hsbaKhoaPhongId)
    {
        $data = $this->hsbaKhoaPhongRepository->getHsbaKhoaPhongById($hsbaKhoaPhongId);
        
        return $data;
    }
    
    public function getLichSuKhamDieuTri($id)
    {
        $data = $this->hsbaKhoaPhongRepository->getLichSuKhamDieuTri($id);
        return $data;
    }    
}
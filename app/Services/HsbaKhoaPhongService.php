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
    
    public function getList($phongId, $benhVienId, $startDay, $endDay, $limit, $page, $keyword, $status)
    {
        $data = $this->hsbaKhoaPhongRepository->getList($phongId, $benhVienId, $startDay, $endDay, $limit, $page, $keyword, $status);
        
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
}
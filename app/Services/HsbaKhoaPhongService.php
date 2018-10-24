<?php
namespace App\Services;

use App\Models\HsbaKhoaPhong;
use App\Http\Resources\HsbaKhoaPhongResource;
use App\Repositories\HsbaKhoaPhong\HsbaKhoaPhongRepository;
use Illuminate\Http\Request;
use Validator;

class HsbaKhoaPhongService 
{
    public function __construct(HsbaKhoaPhongRepository $hsbaKhoaPhongRepository)
    {
        $this->hsbaKhoaPhongRepository = $hsbaKhoaPhongRepository;
    }
    
    public function getListBenhNhanHanhChanh($benhVienId, $startDay, $endDay, $limit, $page, $keyword)
    {
        $data = $this->hsbaKhoaPhongRepository->getListBenhNhanHanhChanh($benhVienId, $startDay, $endDay, $limit, $page, $keyword);
        
        return $data;
    }
    
    public function getListBenhNhanPhongKham($phongId, $benhVienId, $startDay, $endDay, $limit, $page, $keyword)
    {
        $data = $this->hsbaKhoaPhongRepository->getListBenhNhanPhongKham($phongId, $benhVienId, $startDay, $endDay, $limit, $page, $keyword);
        
        return $data;
    }
}
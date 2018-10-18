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
    
    public function getListBenhNhanHanhChanh($startDay, $endDay, $offset, $limit, $keyword)
    {
        $data = $this->hsbaKhoaPhongRepository->getListBenhNhanHanhChanh($startDay, $endDay, $offset, $limit, $keyword);
        
        return $data;
    }
    
    public function getListBenhNhanPhongKham($phongId, $startDay, $endDay, $offset, $limit, $keyword)
    {
        $data = $this->hsbaKhoaPhongRepository->getListBenhNhanPhongKham($phongId, $startDay, $endDay, $offset, $limit, $keyword);
        
        return $data;
    }
}
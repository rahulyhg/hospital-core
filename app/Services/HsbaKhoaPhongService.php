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
    
    public function getListBN_HC($startDay, $endDay, $offset, $limit, $keyword)
    {
        $data = $this->hsbaKhoaPhongRepository->getListBN_HC($startDay, $endDay, $offset, $limit, $keyword);
        
        return $data;
    }
    
    public function getListBN_PK($phongId, $startDay, $endDay, $offset, $limit, $keyword)
    {
        $data = $this->hsbaKhoaPhongRepository->getListBN_PK($phongId, $startDay, $endDay, $offset, $limit, $keyword);
        
        return $data;
    }
}
<?php
namespace App\Services;

use App\Models\HsbaKhoaPhong;
use App\Http\Resources\HsbaKhoaPhongResource;
use App\Repositories\HsbaKhoaPhong\HsbaKhoaPhongRepository;
use Illuminate\Http\Request;
use Validator;

class HsbaKhoaPhongService 
{
    public function __construct(HsbaKhoaPhongRepository $HsbaKhoaPhongRepository)
    {
        $this->HsbaKhoaPhongRepository = $HsbaKhoaPhongRepository;
    }
    
    public function getListBN_HC($start_day, $end_day, $offset, $limit, $keyword)
    {
        $data = $this->HsbaKhoaPhongRepository->getListBN_HC($start_day, $end_day, $offset, $limit, $keyword);
        
        return $data;
    }
    
    public function getListBN_PK($phong_id, $start_day, $end_day, $offset, $limit, $keyword)
    {
        $data = $this->HsbaKhoaPhongRepository->getListBN_PK($phong_id, $start_day, $end_day, $offset, $limit, $keyword);
        
        return $data;
    }
}
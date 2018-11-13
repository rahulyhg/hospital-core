<?php
namespace App\Services;

use App\Http\Resources\KhuVucResource;
use App\Repositories\KhuVucRepository;
use Illuminate\Http\Request;
use Validator;

class KhuVucService
{
    public function __construct(KhuVucRepository $khuVucRepository)
    {
        $this->khuVucRepository = $khuVucRepository;
    }
    
    public function getListKhuVuc($loai,$benhVienId)
    {
        return KhuVucResource::collection(
           $this->khuVucRepository->getListKhuVuc($loai,$benhVienId)
        );
    }
    
}
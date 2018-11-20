<?php

namespace App\Services;

use App\Models\Khoa;
use App\Http\Resources\KhoaResource;
use App\Repositories\KhoaRepository;
use Illuminate\Http\Request;
use Validator;

class KhoaService {
    public function __construct(KhoaRepository $khoaRepository)
    {
        $this->khoaRepository = $khoaRepository;
    }

    public function getListKhoa($loaiKhoa, $benhVienId)
    {
        return KhoaResource::collection(
           $this->khoaRepository->getListKhoa($loaiKhoa, $benhVienId)
        );
    }
    
    
}
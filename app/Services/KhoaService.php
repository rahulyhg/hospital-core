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
    
    public function listKhoaByBenhVienId($benhVienId)
    {
        $data = $this->khoaRepository->listKhoaByBenhVienId($benhVienId);
        return $data;   
    }    
    
    public function getTreeListKhoaPhong($limit, $page, $benhVienId)
    {
        $data = $this->khoaRepository->getTreeListKhoaPhong($limit, $page, $benhVienId);
        return $data;
    }    
    
    public function createKhoa($benhVienId, array $input)
    {
        return $this->khoaRepository->createKhoa($benhVienId, $input);
    }    
    
}
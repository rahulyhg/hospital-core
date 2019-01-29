<?php

namespace App\Services;

use App\Http\Resources\DanhMucBenhVienResource;
use App\Repositories\DanhMuc\DanhMucBenhVienRepository;
use Illuminate\Http\Request;

class DanhMucBenhVienService{
    public function __construct(DanhMucBenhVienRepository $repository)
    {
        $this->danhMucBenhVienRepository = $repository;        
    }
    
    public function getDanhMucBenhVien()
    {
        return DanhMucBenhVienResource::collection(
           $this->danhMucBenhVienRepository->getDanhMucBenhVien()
        );
    }

}
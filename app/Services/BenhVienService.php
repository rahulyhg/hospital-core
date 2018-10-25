<?php

namespace App\Services;

use App\Http\Resources\BenhVienResource;
use App\Repositories\BenhVienRepository;
use Illuminate\Http\Request;

class BenhVienService{
    public function __construct(BenhVienRepository $repository)
    {
        $this->benhVienRepository = $repository;        
    }
    
    public function listBenhVien()
    {
        return BenhVienResource::collection(
           $this->benhVienRepository->listBenhVien()
        );
    }

}
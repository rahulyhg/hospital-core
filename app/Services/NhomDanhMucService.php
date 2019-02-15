<?php

namespace App\Services;

use App\Http\Resources\NhomDanhMucResource;
use App\Repositories\DanhMuc\NhomDanhMucRepository;
use Illuminate\Http\Request;

class NhomDanhMucService
{
    public function __construct(NhomDanhMucRepository $repository)
    {
        $this->repository = $repository;        
    }
    
    public function getListNhomDanhMuc()
    {
        $data = $this->repository->getListNhomDanhMuc();
        return $data;
    }
    
    public function getNhomDmById($id)
    {
        $data = $this->repository->getNhomDmById($id);
        
        return $data;
    }

    public function createNhomDanhMuc(array $input)
    {
        $id = $this->repository->createNhomDanhMuc($input);
        return $id;
    }
    
    public function updateNhomDanhMuc($id, array $input)
    {
        $this->repository->updateNhomDanhMuc($id, $input);
    }
   
}
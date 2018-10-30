<?php

namespace App\Services;

use App\Http\Resources\DanhMucDichVuResource;
use App\Repositories\DanhMucDichVuRepository;
use Illuminate\Http\Request;

class DanhMucDichVuService
{
    public function __construct(DanhMucDichVuRepository $repository)
    {
        $this->repository = $repository;        
    }
    
    public function getListYeuCauKham(Request $request)
    {
        //$offset = $request->query('offset',0);
        
        return DanhMucDichVuResource::collection(
           $this->repository->getDataYeuCauKham($request)
        );
    }
    
    public function getListDanhMucDichVu($limit, $page)
    {
        $data = $this->repository->getListDanhMucDichVu($limit, $page);
        
        return $data;
    }

}
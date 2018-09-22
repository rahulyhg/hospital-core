<?php

namespace App\Services;

use App\Http\Resources\ServicepricerefResource;
use App\Repositories\ServicepricerefRepository;
use Illuminate\Http\Request;

class ServicepricerefService{
    public function __construct(ServicepricerefRepository $repository)
    {
        $this->repository = $repository;        
    }
    
    public function getListYeuCauKham(Request $request)
    {
        $offset = $request->query('offset',0);
        
        return ServicepricerefResource::collection(
           $this->repository->getDataYeuCauKham($offset, $request)
        );
    }

}
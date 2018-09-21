<?php

namespace App\Services;

use App\Http\Resources\BenhVienResource;
use App\Repositories\BenhVienRepository;
use Illuminate\Http\Request;
use Validator;

class BenhVienService {
    public function __construct(BenhVienRepository $BenhVienRepository)
    {
        $this->BenhVienRepository = $BenhVienRepository;
    }

    public function getListBenhVien(Request $request)
    {
        return BenhVienResource::collection(
           $this->BenhVienRepository->getDataListBenhVien($request)
        );
    }
}
<?php

namespace App\Services;

use App\Repositories\PhieuKhamRepository;
use Illuminate\Http\Request;
use Validator;

class PhieuKhamService {
    
    public function __construct(PhieuKhamRepository $phieuKhamRepository)
    {
        $this->phieuKhamRepository = $phieuKhamRepository;
    }

    public function getListPhieuKham($limit, $page)
    {
        return $this->phieuKhamRepository->getListPhieuKham($limit, $page);
    }
    
    public function getListYLenhByPhieuKham($phieuKhamId, $limit, $page)
    {
        return $this->phieuKhamRepository->getListYLenhByPhieuKham($phieuKhamId, $limit, $page);
    }

    
}
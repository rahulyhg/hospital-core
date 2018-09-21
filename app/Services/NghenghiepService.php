<?php

namespace App\Services;

use App\Models\Department;
use App\Http\Resources\NgheNghiepResource;
use App\Repositories\NgheNghiepRepository;
use Illuminate\Http\Request;
use Validator;

class NghenghiepService {
    public function __construct(NgheNghiepRepository $NgheNghiepRepository)
    {
        $this->NgheNghiepRepository = $NgheNghiepRepository;
    }

    public function GetListNgheNghiep(Request $request)
    {
        return NgheNghiepResource::collection(
           $this->NgheNghiepRepository->getDataListNgheNghiep($request)
        );
    }
}
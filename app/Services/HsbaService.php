<?php
namespace App\Services;

use App\Http\Resources\HsbaResource;
use App\Http\Resources\PatientResource;
use App\Repositories\Hsba\HsbaRepository;
use Illuminate\Http\Request;
use Validator;

class HsbaService
{
    public function __construct(HsbaRepository $HsbaRepository)
    {
        $this->HsbaRepository = $HsbaRepository;
    }
    
    public function getHsbaByBenhNhanId($benh_nhan_id)
    {
        $data = $this->HsbaRepository->getHsbaByBenhNhanId($benh_nhan_id);
         
        return new HsbaResource($data);
    }
    
    public function getHsbaByHsbaId($hsba_id, $phong_id)
    {
        $data = $this->HsbaRepository->getHsbaByHsbaId($hsba_id, $phong_id);
         
        return $data;
    }
}
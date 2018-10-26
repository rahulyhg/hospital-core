<?php
namespace App\Services;

use App\Http\Resources\HsbaResource;
use App\Http\Resources\PatientResource;
use App\Repositories\Hsba\HsbaRepository;
use Illuminate\Http\Request;
use Validator;

class HsbaService
{
    public function __construct(HsbaRepository $hsbaRepository)
    {
        $this->hsbaRepository = $hsbaRepository;
    }
    
    public function getHsbaByBenhNhanId($benhNhanId)
    {
        $data = $this->hsbaRepository->getHsbaByBenhNhanId($benhNhanId);
         
        return new HsbaResource($data);
    }
    
    public function getHsbaByHsbaId($hsbaId, $phongId)
    {
        $data = $this->hsbaRepository->getHsbaByHsbaId($hsbaId, $phongId);
         
        return $data;
    }
    
    public function updateHsba($hsbaId, Request $request)
    {
        $this->hsbaRepository->updateHsba($hsbaId, $request);
    }
}
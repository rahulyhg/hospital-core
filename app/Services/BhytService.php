<?php
namespace App\Services;

use App\Http\Resources\HsbaResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\BhytResource;
use App\Repositories\Bhyt\BhytRepository;
use Illuminate\Http\Request;
use Validator;

class BhytService
{
    public function __construct(BhytRepository $bhytRepository)
    {
        $this->bhytRepository = $bhytRepository;
    }
    
    public function getTypePatientByCode($bhytCode)
    {
        $dataPatient = $this->bhytRepository->getTypePatientByCode($bhytCode);
        
        return new HsbaResource($dataPatient);
    }
    
    public function updateBhyt($hsbaId, Request $request)
    {
        $this->bhytRepository->updateBhyt($hsbaId, $request);
    }
    
    public function getMaBhytTreEm($maTinh)
    {
        $data = $this->bhytRepository->getMaBhytTreEm($maTinh);
        return $data;
    }    
}
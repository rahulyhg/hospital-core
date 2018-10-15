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
    public function __construct(BhytRepository $BhytRepository)
    {
        $this->BhytRepository = $BhytRepository;
    }
    
    public function getTypePatientByCode($bhytcode)
    {
        $datapatient = $this->BhytRepository->getTypePatientByCode($bhytcode);
        
        return new HsbaResource($datapatient);
    }
}
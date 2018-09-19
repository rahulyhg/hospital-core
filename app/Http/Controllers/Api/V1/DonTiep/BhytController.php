<?php

namespace App\Http\Controllers\Api\V1\DonTiep;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BhytService;

class BhytController extends Controller
{
    //
     /**
     * __construct.
     *
     * @param $service
     */
    public function __construct(BhytService $service)
    {
        
        $this->service = $service;
    }
    public function getTypePatientByCode($bhytcode)
    {
        $data = $this->service->getTypePatientByCode($bhytcode);
        //return $data['patientid'];
        //return $data['hosobenhanid'];
        return $data;
        //echo
    }
}

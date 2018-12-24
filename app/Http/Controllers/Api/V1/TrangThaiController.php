<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Services\TrangThaiService;
use Carbon\Carbon;

class TrangThaiController extends APIController
{
    
    public function __construct(TrangThaiService $service)
    {
        $this->service = $service;
    }
    
    public function batDauKhamBN($hsbakpId) {
        $this->service->batDauKhamBN($hsbakpId);
    } 
}
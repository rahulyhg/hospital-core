<?php
namespace App\Http\Controllers\Api\V1\PhongKham;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\SttPhongKhamService;

class SttPhongKhamController extends APIController
{
    public function __construct(SttPhongKhamService $sttPhongKhamService)
    {
        $this->sttPhongKhamService = $sttPhongKhamService;
    }
    
}
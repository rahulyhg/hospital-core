<?php

namespace App\Http\Controllers\Api\V1\UserSetting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\KhuVucService;
use App\Services\QuayService;

class UserSettingController extends Controller
{
    //
     /**
     * __construct.
     *
     * @param $service
     */
    public function __construct(KhuVucService $khuVucService, QuayService $quayService)
    {
        $this->khuVucService = $khuVucService;
        $this->quayService = $quayService;
    }
    public function getListKhuVuc(Request $request)
    {
        $dataSet = $this->khuVucService->getListKhuVuc($request->loai,$request->benhVienId);
        return $dataSet;
    }
    
    public function getListQuay(Request $request)
    {
        $dataSet = $this->quayService->getListQuay($request->khuVucId,$request->benhVienId);
        return $dataSet;
    }
}

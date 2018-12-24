<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Services\TrangThaiService;
use App\Models\SttPhongKham;
use Carbon\Carbon;

class TrangThaiController extends APIController
{
    
    public function __construct(TrangThaiService $service)
    {
        $this->service = $service;
    }
    
    public function chuyenTTBNSangBatDauKham($sttId) {
        $tableModel = app()->make(SttPhongKham::class);
        $extraUpdate = [
            'thoi_gian_goi' => Carbon::now()->toDateTimeString()
            ];
        $attributes = [
            'statusColumn' => 'trang_thai',
            'newStatus' => 2,
            'idColumn' => 'id',
            'idValue' => $sttId,
            'extraUpdate' => $extraUpdate
        ];
        $this->service->changeToState($tableModel, $attributes);
    } 
}
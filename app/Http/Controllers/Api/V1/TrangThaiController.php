<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Services\TrangThaiService;
use App\Models\HsbaKhoaPhong;
use Carbon\Carbon;

class TrangThaiController extends APIController
{
    
    public function __construct(TrangThaiService $service)
    {
        $this->service = $service;
    }
    
    public function batDauKhamBN($hsbakpId) {
        //set hsba_khoa_phong.trang_thai = 1
        $tableModel = app()->make(HsbaKhoaPhong::class);
        $extraUpdate = [
            'thoi_gian_vao_vien' => Carbon::now()->toDateTimeString()
            ];
        $attributes = [
            'statusColumn' => 'trang_thai',
            'newStatus' => 1,
            'idColumn' => 'id',
            'idValue' => $hsbakpId,
            'extraUpdate' => $extraUpdate
        ];
        $this->service->changeToState($tableModel, $attributes);
        
        $data = $tableModel->findOrFail($hsbakpId);
        $this->respond($data);
    } 
}
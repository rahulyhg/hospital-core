<?php
namespace App\Services;

use App\Repositories\TrangThaiRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\HsbaKhoaPhong;
use Illuminate\Support\Facades\Config;


class TrangThaiService
{
    public function __construct(TrangThaiRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function batDauKhamBN($hsbakpId) {
        $tableModel = app()->make(HsbaKhoaPhong::class);
        $extraUpdate = [
            'thoi_gian_vao_vien' => Carbon::now()->toDateTimeString()
            ];
        $attributes = [
            'statusColumn' => 'trang_thai',
            'newStatus' => Config::get('constants.trang_thai_bat_dau_kham'),
            'idColumn' => 'id',
            'idValue' => $hsbakpId,
            'extraUpdate' => $extraUpdate
        ];
        $this->repository->changeToState($tableModel, $attributes);
    } 
}
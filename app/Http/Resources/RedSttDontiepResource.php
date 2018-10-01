<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class RedSttDontiepResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'loai_stt'              => $this->loai_stt,
            'so_thu_tu'             => $this->so_thu_tu,
            'trang_thai'            => $this->trang_thai,
            'thoi_gian_phat'        => $this->thoi_gian_phat,
            'thoi_gian_goi'         => $this->thoi_gian_goi,
            'thoi_gian_ket_thuc'    => $this->thoi_gian_ket_thuc,
            'ma_so_kiosk'           => $this->ma_so_kiosk,
            'id_phong'              => $this->id_phong,
            'id_benh_vien'          => $this->id_benh_vien,
            'thong_tin_so_bo'       => json_decode($this->thong_tin_so_bo)
        ];
    }
}
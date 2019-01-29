<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SttPhongKhamResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'loai_stt'              => $this->loai_stt,
            'so_thu_tu'             => $this->so_thu_tu,
            'in_so'                 => $this->loai_stt.sprintf('%03d',$this->so_thu_tu),
            'trang_thai'            => $this->trang_thai,
            'thoi_gian_phat'        => $this->thoi_gian_phat,
            'thoi_gian_goi'         => $this->thoi_gian_goi,
            'thoi_gian_ket_thuc'    => $this->thoi_gian_ket_thuc,
            'ten_benh_nhan'         => $this->ten_benh_nhan,
            'phong_id'              => $this->phong_id,
            'khoa_id'               => $this->khoa_id,
            'benh_vien_id'          => $this->benh_vien_id,
            'hsba_id'               => $this->hsba_id,
            'hsba_khoa_phong_id'    => $this->hsba_khoa_phong_id,
            'auth_users_id'         => $this->auth_users_id,
            'stt_don_tiep_id'       => $this->stt_don_tiep_id,
            'ten_phong'             => $this->ten_phong
        ];
    }
}
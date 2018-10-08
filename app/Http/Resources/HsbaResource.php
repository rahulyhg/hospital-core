<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;

class HsbaResource extends Resource
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
            'id'                        => $this->id,
            'gioi_tinh'                 => $this->gioi_tinh,
            'nghe_nghiep_id'            => $this->nghe_nghiep_id,
            'dan_toc_id'                => $this->dan_toc_id,
            'quoc_tich_id'              => $this->quoc_tich_id,
            'loai_benh_an'              => $this->loai_benh_an,
            'ten_benh_nhan'             => $this->ten_benh_nhan,
            'benh_nhan_id'              => $this->benh_nhan_id,
            'ms_bhyt'                   => $this->ms_bhyt,
            'ngay_sinh'                 => $this->ngay_sinh,
            'nam_sinh'                  => $this->nam_sinh,
            'tuoi'                      => Carbon::parse($this->ngay_sinh)->diffInYears(Carbon::now()),
            'so_nha'                    => $this->so_nha,
            'duong_thon'                => $this->duong_thon,
            'phuong_xa_id'              => $this->phuong_xa_id,
            'quan_huyen_id'             => $this->quan_huyen_id,
            'tinh_thanh_pho_id'         => $this->tinh_thanh_pho_id,
            'noi_lam_viec'              => $this->noi_lam_viec
        ];
    }
}
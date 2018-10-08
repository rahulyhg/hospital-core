<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;

class HosobenhanResource extends Resource
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
            'id'              => $this->id,
            'gioi_tinh'              => $this->gioi_tinh,
            'nghe_nghiep_id'            => $this->nghe_nghiep_id,
            'ten_nghe_nghiep'            => $this->ten_nghe_nghiep,
            'dan_toc_id'             => $this->dan_toc_id,
            'ten_dan_toc'             => $this->ten_dan_toc,
            'quoc_tich_id'            => $this->quoc_tich_id,
            'ten_quoc_tich'            => $this->ten_quoc_tich,
            'loai_benh_an'              => $this->loai_benh_an,
            'ten_benh_nhan'               => $this->ten_benh_nhan,
            'benh_nhan_id'                 => $this->benh_nhan_id,
            'ms_bhyt'                  => $this->ms_bhyt,
            'ngay_sinh'                  => $this->ngay_sinh,
            'nam_sinh'             => $this->nam_sinh,
            'tuoi'                       => Carbon::parse($this->ngay_sinh)->diffInYears(Carbon::now()),
            'dia_chi'                    => sprintf("%s %s, %s, %s, %s", $this->so_nha, $this->duong_thon, $this->ten_phuong_xa, $this->ten_quan_huyen, $this->ten_tinh_thanh_pho),
            'noi_lam_viec'                => $this->noi_lam_viec
        ];
    }
}
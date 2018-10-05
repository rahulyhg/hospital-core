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
            'hosobenhanid'              => $this->id,
            'gioitinhcode'              => $this->gioi_tinh,
            //'gioitinhname'              => $this->gioitinhname,
            'nghenghiepcode'            => $this->nghe_nghiep_id,
            'nghenghiepname'            => $this->ten_nghe_nghiep,
            'hc_dantoccode'             => $this->dan_toc_id,
            'hc_dantocname'             => $this->ten_dan_toc,
            'hc_quocgiacode'            => $this->quoc_tich_id,
            'hc_quocgianame'            => $this->ten_quoc_tich,
            'loaibenhanid'              => $this->loai_benh_an,
            'patientname'               => $this->ten_benh_nhan,
            'patientid'                 => $this->benh_nhan_id,
            //'patientcode'               => $this->patientcode,
            'bhytcode'                  => $this->ms_bhyt,
            'birthday'                  => $this->ngay_sinh,
            'birthday_year'             => $this->nam_sinh,
            'age'                       => Carbon::parse($this->ngay_sinh)->diffInYears(Carbon::now()),
            'diachi'                    => sprintf("%s %s, %s, %s, %s", $this->so_nha, $this->duong_thon, $this->ten_phuong_xa, $this->ten_quan_huyen, $this->ten_tinh_thanh_pho),
            'noilamviec'                => $this->noi_lam_viec
        ];
    }
}
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
            'hosobenhanid'              => $this->hosobenhanid,
            'gioitinhcode'              => $this->gioitinhcode,
            'gioitinhname'              => $this->gioitinhname,
            'nghenghiepcode'            => $this->nghenghiepcode,
            'nghenghiepname'            => $this->nghenghiepname,
            'hc_dantoccode'             => $this->hc_dantoccode,
            'hc_dantocname'             => $this->hc_dantocname,
            'hc_quocgiacode'            => $this->hc_quocgiacode,
            'hc_quocgianame'            => $this->hc_quocgianame,
            'loaibenhanid'              => $this->loaibenhanid,
            'patientname'               => $this->patientname,
            'patientid'                 => $this->patientid,
            'patientcode'               => $this->patientcode,
            'bhytcode'                  => $this->bhytcode,
            'birthday'                  => $this->birthday,
            'birthday_year'             => $this->birthday_year,
            'age'                       => Carbon::parse($this->birthday)->diffInYears(Carbon::now()),
            'diachi'                    => sprintf("%s %s, %s, %s, %s", $this->hc_sonha, $this->hc_thon, $this->hc_xaname, $this->hc_huyenname, $this->hc_tinhname),
            'noilamviec'                => $this->noilamviec
        ];
    }
}
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
            'hosobenhanid'                => $this->hosobenhanid,
            'gioitinhcode'                => $this->gioitinhcode,
            'nghenghiepcode'              => $this->nghenghiepcode,
            'hc_dantoccode'               => $this->hc_dantoccode,
            'hc_quocgiacode'              => $this->hc_quocgiacode,
            'hc_dantocname'               => $this->hc_dantocname,
            'hc_quocgianame'              => $this->hc_quocgianame,
            'loaibenhanid'                => $this->loaibenhanid,
            'patientname'                 => $this->patientname,
            'patientid'                   => $this->patientid,
            'patientcode'                 => $this->patientcode,
            'hosobenhanid'                => $this->hosobenhanid,
            'birthday'                    => $this->birthday,
            'birthday_year'               => $this->birthday_year,
            'age'                         => Carbon::parse($this->birthday)->diffInYears(Carbon::now()),
            ];
    }
}
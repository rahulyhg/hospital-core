<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

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
            'loaibenhanid'                => $this->loaibenhanid,
            'patientname'                 => $this->patientname,
            'patientid'                   => $this->patientid,
            'hosobenhanid'                => $this->hosobenhanid,
            'birthday'                    => $this->birthday,
            'birthday_year'               => $this->birthday_year,
            ];
    }
}
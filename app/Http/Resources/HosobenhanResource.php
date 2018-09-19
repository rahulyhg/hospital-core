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
            'loaibenhanid'                => $this->loaibenhanid,
            'patientname'                 => $this->patientname,
            'patientid'                   => $this->patientid,
            'hosobenhanid'                => $this->hosobenhanid,
            'birthday'                    => $this->birthday,
            'birthday_year'               => $this->birthday_year,
            'age'                         => Carbon::parse($this->birthday)->diffInYears(Carbon::now()),
            ];
    }
}
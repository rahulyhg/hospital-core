<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class NgheNghiepResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'nghenghiepdbid'      => $this->nghenghiepdbid,
            'nghenghiepcode'    => $this->nghenghiepcode,
            'nghenghiepname'    => $this->nghenghiepname
        ];
    }
}
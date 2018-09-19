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
            'id'                => $this->id,
            'loai_stt'          => $this->loai_stt,
            'sothutunumber'     => $this->sothutunumber,
            'trangthai'         => $this->trangthai,
            'ngayphat'          => $this->ngayphat,
            'ngaygoi'           => $this->ngaygoi,
            'khuvuc'            => $this->khuvuc
        ];
    }
}
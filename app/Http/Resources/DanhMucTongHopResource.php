<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DanhMucTongHopResource extends Resource
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
            'id'      => $this->id,
            'khoa'    => $this->khoa,
            'gia_tri'    => $this->gia_tri,
            'dien_giai'    => $this->dien_giai
        ];
    }
}
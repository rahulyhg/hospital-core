<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DanhMucDichVuResource extends Resource
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
            'id'   => $this->id,
            'ten'    => $this->ten,
            'gia'    => $this->gia,
            // 'gia_nhan_dan'    => $this->gia_nhan_dan,
            'gia_bhyt'    => $this->gia_bhyt,
            'gia_nuoc_ngoai'    => $this->gia_nuoc_ngoai
        ];
    }
}
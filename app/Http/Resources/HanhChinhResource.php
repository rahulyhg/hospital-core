<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class HanhChinhResource extends Resource
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
            'ma_tinh'      => $this->ma_tinh,
            'ten_tinh'    => $this->ten_tinh,
            'ma_huyen'      => $this->ma_huyen,
            'ten_huyen'    => $this->ten_huyen,
            'ma_xa'      => $this->ma_xa,
            'ten_xa'    => $this->ten_xa
        ];
    }
}
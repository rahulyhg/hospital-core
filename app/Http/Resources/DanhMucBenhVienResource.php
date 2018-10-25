<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DanhMucBenhVienResource extends Resource
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
            'ma_kcbbd'    => $this->ma_kcbbd,
            'ma'    => $this->ma,
            'ten'    => $this->ten,
            'dia_chi'    => $this->dia_chi,
            'hang'    => $this->hang,
            'loai'    => $this->loai,
            'tuyen'    => $this->tuyen,
            'ghi_chu'    => $this->ghi_chu,
            'ma_tinh'    => $this->ma_tinh,
            'ma_huyen'    => $this->ma_huyen,
            'ma_xa'    => $this->ma_xa
        ];
    }
}
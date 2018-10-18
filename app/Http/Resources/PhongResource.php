<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PhongResource extends Resource
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
            'ma_phong'    => $this->ma_phong,
            'ten_phong'    => $this->ten_phong,
            'khoa_id' => $this->khoa_id,
            'loai_phong'    => $this->loai_phong
          
        ];
    }
}
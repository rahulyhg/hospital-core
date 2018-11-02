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
            'ma_nhom'    => $this->ma_nhom,
            'ten_nhom'      => $this->ten_nhom
        ];
    }
}
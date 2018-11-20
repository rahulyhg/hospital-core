<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class KhoaResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id'                        => $this->id,
            'ma_khoa'                   => $this->ma_khoa,
            'ten_khoa'                  => $this->ten_khoa,
            'loai_khoa'                 => $this->loai_khoa,
        ];
    }
}
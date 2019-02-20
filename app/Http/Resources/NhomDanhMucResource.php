<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class NhomDanhMucResource extends Resource
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
            'id'                    => $this->id,
            'ten_danh_muc'          => $this->ten_danh_muc,
            'parent_id'             => $this->parent_id,
            'trang_thai_su_dung'    => $this->trang_thai_su_dung
        ];
    }
}